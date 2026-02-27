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
        // Here we build the Prompt
        $prompt = "You are an expert C++ programming mentor. Evaluate this code.
                   Context: {$context}
                   Code: {$code}
                   Attempt Number: {$attemptCount}
                   Return exactly JSON format: {score, logical_errors[], complexity_feedback, general_feedback, hint}";

        // Simulating structured JSON response
        $mockJson = '{
            "score": 80,
            "logical_errors": ["The loop runs N+1 times instead of N."],
            "complexity_feedback": "Time complexity is O(N) which is great!",
            "general_feedback": "عمل رائع يا بطل! انتبه لعدد دورات الحلقة.",
            "hint": "راجع الشرط في الحلقة، هل تحتاج إلى <= أم < ؟"
        }';
        return $mockJson;
    }
}
