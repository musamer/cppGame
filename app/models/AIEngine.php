<?php
/*
 * AI Grading Engine
 * Sends code to AI API, parses JSON response, and records feedback
 */
class AIEngine extends Model
{

    // Evaluate code and get feedback
    public function evaluateCode($submissionId, $code, $conceptContext, $attemptCount)
    {
        // Mocking the AI Call. In reality, use cURL to OpenAI / Anthropic
        $mockApiResponse = $this->callOpenAIAPI($code, $conceptContext, $attemptCount);

        // Parse JSON
        $feedback = json_decode($mockApiResponse, true);
        if (!$feedback) {
            return false; // AI Error
        }

        // Save feedback
        $this->db->query("INSERT INTO ai_feedback (submission_id, score_given, logical_errors, complexity_feedback, general_feedback, hints_provided) 
                          VALUES (:sub_id, :score, :logics, :comp, :gen, :hint)");
        $this->db->bind(':sub_id', $submissionId);
        $this->db->bind(':score', $feedback['score']);
        $this->db->bind(':logics', json_encode($feedback['logical_errors']));
        $this->db->bind(':comp', $feedback['complexity_feedback']);
        $this->db->bind(':gen', $feedback['general_feedback']);
        $this->db->bind(':hint', json_encode([$feedback['hint']]));
        $this->db->execute();

        return $feedback;
    }

    private function callOpenAIAPI($code, $context, $attemptCount)
    {
        $prompt = "You are an expert C++ programming mentor and competitive programming coach for teenagers. Evaluate the student's code based on the following context.
Context: {$context}
Student Code:\n```cpp\n{$code}\n```
Attempt Number: {$attemptCount}

Respond completely in native, encouraging, and clear Arabic.
Evaluate the code for correctness, logic, performance, and best practices.
- If the code is just an empty boilerplate/template without solving the problem (e.g. simply `int main() { return 0; }` or empty logic), ALWAYS give it a score of 0, list it as a logical error, and provide a hint indicating they need to write the actual solution.
- If the code genuinely solves the problem described in the context accurately and handles test cases, give a score of 100, provide positive `general_feedback`, and leave `hint` empty.
- If the code has logic issues or partially solves it, give a score between 10 and 90 depending on magnitude of correctness, explain the flaws constructively in `general_feedback`, and provide a subtle helpful `hint`.
- Make sure that both `logical_errors` and `complexity_feedback` are accurately populated in Arabic.

You MUST respond strictly with a valid JSON object matching this structure EXACTLY. Ensure the output is valid JSON:
{
    \"score\": 80,
    \"logical_errors\": [\"error 1\", \"error 2\"],
    \"complexity_feedback\": \"Your time/space complexity evaluation\",
    \"general_feedback\": \"General assessment message in Arabic\",
    \"hint\": \"Subtle hint in Arabic, leave empty if score is 100\"
}";

        $apiKey = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : '';
        if (empty($apiKey)) {
            return json_encode([
                "score" => 0,
                "logical_errors" => ["Missing API Key."],
                "complexity_feedback" => "",
                "general_feedback" => "يرجى التحقق من إعدادات مفتاح OpenAI API.",
                "hint" => ""
            ]);
        }

        $url = "https://api.openai.com/v1/chat/completions";

        $data = [
            "model" => "gpt-4o",
            "messages" => [
                ["role" => "system", "content" => "You are an expert C++ mentor. You strictly return JSON."],
                ["role" => "user", "content" => $prompt]
            ],
            "response_format" => ["type" => "json_object"],
            "temperature" => 0.5
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey
        ]);

        // Timeout settings so it doesn't freeze the app indefinitely
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        // Disable SSL Verify for Laragon/XAMPP local environments
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode == 200 && $response) {
            $responseData = json_decode($response, true);
            return $responseData['choices'][0]['message']['content'];
        }

        // Fallback or error logging
        return json_encode([
            "score" => 0,
            "logical_errors" => ["AI API Error: " . $error],
            "complexity_feedback" => "Http Code: " . $httpCode,
            "general_feedback" => "خطأ في الاتصال بالفرع الذكي: " . $error . " (HTTP " . $httpCode . ")",
            "hint" => "استجابة الخادم: " . ($response ?: 'لا يوجد')
        ]);
    }
}
