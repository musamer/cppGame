<?php
/*
 * Spaced Reinforcement Engine
 * Tracks user understanding of concepts and schedules reviews
 */
class SpacedEngine extends Model
{

    // Update concept mastery based on latest exercise score
    public function recordAttempt($userId, $conceptId, $score)
    {
        // Check if record exists
        $this->db->query("SELECT * FROM spaced_reinforcement WHERE user_id = :uid AND concept_id = :cid");
        $this->db->bind(':uid', $userId);
        $this->db->bind(':cid', $conceptId);
        $record = $this->db->single();

        if ($record) {
            // Update existing record
            $failCount = $record->failure_count;
            if ($score < 60) {
                $failCount++;
                $status = 'struggling';
                // Schedule review for tomorrow
                $nextReview = date('Y-m-d', strtotime('+1 day'));
            } else {
                $failCount = 0; // Reset
                $status = 'mastered';
                // Schedule next review further away based on algorithm (e.g. 7 days)
                $nextReview = date('Y-m-d', strtotime('+7 days'));
            }

            $this->db->query("UPDATE spaced_reinforcement 
                              SET failure_count = :fail, last_score = :score, status = :status, next_review_date = :nextD 
                              WHERE id = :id");
            $this->db->bind(':fail', $failCount);
            $this->db->bind(':score', $score);
            $this->db->bind(':status', $status);
            $this->db->bind(':nextD', $nextReview);
            $this->db->bind(':id', $record->id);
            $this->db->execute();
        } else {
            // Create new record
            $failCount = ($score < 60) ? 1 : 0;
            $status = ($score < 60) ? 'needs_review' : 'mastered';
            $nextReview = ($score < 60) ? date('Y-m-d', strtotime('+1 day')) : date('Y-m-d', strtotime('+3 days'));

            $this->db->query("INSERT INTO spaced_reinforcement (user_id, concept_id, failure_count, last_score, next_review_date, status)
                              VALUES (:uid, :cid, :fail, :score, :nextD, :status)");
            $this->db->bind(':uid', $userId);
            $this->db->bind(':cid', $conceptId);
            $this->db->bind(':fail', $failCount);
            $this->db->bind(':score', $score);
            $this->db->bind(':nextD', $nextReview);
            $this->db->bind(':status', $status);
            $this->db->execute();
        }
    }
}
