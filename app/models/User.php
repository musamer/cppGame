<?php
class User extends Model
{

    // Register User
    public function register($data)
    {
        // Generate Friend Code
        $friendCode = 'KNIGHT-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));

        $this->db->query('INSERT INTO users (username, email, password_hash, friend_code) VALUES (:username, :email, :password, :friend_code)');
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':friend_code', $friendCode);

        // Execute
        if ($this->db->execute()) {
            $newUserId = $this->db->lastInsertId();

            // Unlock World 1 (The Awakening)
            $this->db->query("INSERT INTO unlocked_worlds (user_id, world_id) VALUES (:uid, 1)");
            $this->db->bind(':uid', $newUserId);
            $this->db->execute();

            // Unlock Stage 1 (World 1, Stage Order 1)
            // Need to get the actual stage ID dynamically or hardcode it safely since the seeder makes it ID 1
            $this->db->query("INSERT INTO unlocked_stages (user_id, stage_id) VALUES (:uid, 1)");
            $this->db->bind(':uid', $newUserId);
            $this->db->execute();

            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password_hash;
            if (password_verify($password, $hashed_password)) {
                // Update last login
                $this->db->query('UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id');
                $this->db->bind(':id', $row->id);
                $this->db->execute();

                return $row;
            }
        }
        return false;
    }

    // Find user by email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by username
    public function findUserByUsername($username)
    {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    /**
     * Get Knight Rank based on XP
     * Calculates the title the user holds in the game.
     */
    public function getRankTitle($xp)
    {
        if ($xp < 60) return 'متدرب (Trainee)';
        if ($xp < 150) return 'فارس مبتدئ (Novice Knight)';
        if ($xp < 300) return 'فارس متمرس (Adept Knight)';
        if ($xp < 500) return 'فارس مقاتل (Warrior Knight)';
        if ($xp < 750) return 'قائد فرسان (Knight Commander)';
        if ($xp < 1000) return 'فارس فضي (Silver Knight)';
        if ($xp < 1150) return 'فارس ذهبي (Golden Knight)';
        if ($xp < 1200) return 'بطل الأسطورة (Legendary Hero)';
        return 'الفارس الأعظم (Grandmaster Knight)';
    }

    /**
     * Get the next rank and the XP required for it.
     */
    public function getNextRankInfo($xp)
    {
        $ranks = [
            60 => 'فارس مبتدئ (Novice Knight)',
            150 => 'فارس متمرس (Adept Knight)',
            300 => 'فارس مقاتل (Warrior Knight)',
            500 => 'قائد فرسان (Knight Commander)',
            750 => 'فارس فضي (Silver Knight)',
            1000 => 'فارس ذهبي (Golden Knight)',
            1150 => 'بطل الأسطورة (Legendary Hero)',
            1200 => 'الفارس الأعظم (Grandmaster Knight)'
        ];

        foreach ($ranks as $threshold => $title) {
            if ($xp < $threshold) {
                return ['next_rank' => $title, 'xp_needed' => $threshold - $xp, 'total_needed' => $threshold];
            }
        }

        return ['next_rank' => 'الحد الأقصى', 'xp_needed' => 0, 'total_needed' => $xp];
    }
}
