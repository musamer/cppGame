<?php
/*
 * Gamification Engine
 * Handles XP allocation, titles, and badges
 */
class GameEngine extends Model
{

    // Add XP to user
    public function addXP($userId, $amount, $sourceType, $sourceId)
    {
        // 1. Log the XP
        $this->db->query("INSERT INTO xp_logs (user_id, xp_amount, source_type, source_id) VALUES (:uid, :amt, :type, :sid)");
        $this->db->bind(':uid', $userId);
        $this->db->bind(':amt', $amount);
        $this->db->bind(':type', $sourceType);
        $this->db->bind(':sid', $sourceId);
        $this->db->execute();

        // 2. Update user total_xp
        $this->db->query("UPDATE users SET total_xp = total_xp + :amt WHERE id = :uid");
        $this->db->bind(':amt', $amount);
        $this->db->bind(':uid', $userId);
        $this->db->execute();

        // 3. Check for Title Unlocks
        $this->checkTitles($userId);
    }

    // Check if user is eligible for new titles based on total XP or completed stages
    private function checkTitles($userId)
    {
        $this->db->query("SELECT total_xp FROM users WHERE id = :uid");
        $this->db->bind(':uid', $userId);
        $user = $this->db->single();
        $xp = $user->total_xp;

        // Fetch all point-based titles user doesn't have
        $this->db->query("SELECT id, condition_value FROM titles 
                          WHERE condition_type = 'points_threshold' 
                          AND id NOT IN (SELECT title_id FROM user_titles WHERE user_id = :uid)");
        $this->db->bind(':uid', $userId);
        $availableTitles = $this->db->resultSet();

        foreach ($availableTitles as $title) {
            if ($xp >= (int)$title->condition_value) {
                // Unlock Title
                $this->db->query("INSERT INTO user_titles (user_id, title_id) VALUES (:uid, :tid)");
                $this->db->bind(':uid', $userId);
                $this->db->bind(':tid', $title->id);
                $this->db->execute();
            }
        }
    }

    // Fetch Leaderboard
    public function getLeaderboard($limit = 10)
    {
        $this->db->query("SELECT username, total_xp, current_level, avatar_url 
                          FROM users 
                          WHERE role = 'student' 
                          ORDER BY total_xp DESC 
                          LIMIT :limit");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}
