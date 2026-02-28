<?php
class Friend extends Model
{
    // Add a friend by code (bidirectional)
    public function addFriend($userId, $friendCode)
    {
        // 1. Find the user by friend_code
        $this->db->query("SELECT id FROM users WHERE friend_code = :code AND id != :uid");
        $this->db->bind(':code', $friendCode);
        $this->db->bind(':uid', $userId);
        $friend = $this->db->single();

        if (!$friend) {
            return "not_found"; // Code doesn't exist or trying to add self
        }

        $friendId = $friend->id;

        // 2. Check if already friends
        $this->db->query("SELECT * FROM friends WHERE (user_id_1 = :u1 AND user_id_2 = :u2) OR (user_id_1 = :u2 AND user_id_2 = :u1)");
        $this->db->bind(':u1', $userId);
        $this->db->bind(':u2', $friendId);
        if ($this->db->rowCount() > 0) {
            return "already_friends";
        }

        // 3. Insert friendship (we can just insert once, but order doesn't matter much)
        // Ensure u1 < u2 to keep unique keys sorted if we wanted, but we already handle it with the ON DUPLICATE or logic
        $u1 = min($userId, $friendId);
        $u2 = max($userId, $friendId);

        $this->db->query("INSERT INTO friends (user_id_1, user_id_2) VALUES (:u1, :u2)");
        $this->db->bind(':u1', $u1);
        $this->db->bind(':u2', $u2);

        if ($this->db->execute()) {
            return "success";
        }

        return "error";
    }

    // Get a user's friends leaderboard/stats
    // It should include the user themselves so they see their relative rank
    public function getFriendsLeaderboard($userId)
    {
        $query = "
            SELECT 
                u.id, u.username, u.total_xp, u.avatar_url,
                (SELECT COUNT(*) FROM unlocked_stages us WHERE us.user_id = u.id) as stages_completed
            FROM users u
            WHERE u.id = :uid
               OR u.id IN (SELECT user_id_2 FROM friends WHERE user_id_1 = :uid)
               OR u.id IN (SELECT user_id_1 FROM friends WHERE user_id_2 = :uid)
            ORDER BY u.total_xp DESC, stages_completed DESC
        ";

        $this->db->query($query);
        $this->db->bind(':uid', $userId);

        return $this->db->resultSet();
    }
}
