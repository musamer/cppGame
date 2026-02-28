<?php
class Submission extends Model
{

    // Save initial code submission
    public function saveSubmission($userId, $exerciseId, $code, $status)
    {
        $this->db->query("INSERT INTO submissions (user_id, exercise_id, submitted_code, status) VALUES (:uid, :eid, :code, :status)");
        $this->db->bind(':uid', $userId);
        $this->db->bind(':eid', $exerciseId);
        $this->db->bind(':code', $code);
        $this->db->bind(':status', $status);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Update submission status and score after AI evaluation
    public function updateSubmissionScore($submissionId, $status, $score)
    {
        $this->db->query("UPDATE submissions SET status = :status, score = :score WHERE id = :sid");
        $this->db->bind(':status', $status);
        $this->db->bind(':score', $score);
        $this->db->bind(':sid', $submissionId);

        return $this->db->execute();
    }

    // Get the maximum previous score a user has achieved for an exercise
    public function getMaxScore($userId, $exerciseId)
    {
        $this->db->query("SELECT MAX(score) as max_score FROM submissions WHERE user_id = :uid AND exercise_id = :eid");
        $this->db->bind(':uid', $userId);
        $this->db->bind(':eid', $exerciseId);
        $row = $this->db->single();
        return $row && $row->max_score !== null ? (int)$row->max_score : 0;
    }
}
