<?php
class Instructor extends Model
{
    // Get general statistics for the dashboard
    public function getDashboardStats()
    {
        // Total Students
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
        $totalStudents = $this->db->single()->count;

        // Total Submissions
        $this->db->query("SELECT COUNT(*) as count FROM submissions");
        $totalSubmissions = $this->db->single()->count;

        // Average Score (Only for passed/failed to avoid pending)
        $this->db->query("SELECT AVG(score) as avg_score FROM submissions WHERE status != 'pending'");
        $avgScore = round($this->db->single()->avg_score ?? 0, 1);

        return [
            'total_students' => $totalStudents,
            'total_submissions' => $totalSubmissions,
            'avg_score' => $avgScore
        ];
    }

    // Get the top struggling exercises (lowest average score and high failure counts)
    public function getStrugglingExercises($limit = 5)
    {
        $this->db->query("
            SELECT e.id, e.title, 
                   (SELECT COUNT(*) FROM submissions s WHERE s.exercise_id = e.id) as attempt_count,
                   (SELECT AVG(score) FROM submissions s WHERE s.exercise_id = e.id) as avg_score,
                   (SELECT COUNT(*) FROM submissions s WHERE s.exercise_id = e.id AND s.status = 'failed') as failure_count
            FROM exercises e
            HAVING attempt_count > 0
            ORDER BY avg_score ASC, failure_count DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Get real-time recent submissions across the platform
    public function getRecentSubmissions($limit = 10)
    {
        $this->db->query("
            SELECT s.id, s.score, s.status, s.created_at,
                   u.username as student_name, u.avatar_url,
                   e.title as exercise_title
            FROM submissions s
            JOIN users u ON s.user_id = u.id
            JOIN exercises e ON s.exercise_id = e.id
            ORDER BY s.created_at DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Get leaderboard of top students globally
    public function getTopStudents($limit = 5)
    {
        $this->db->query("
            SELECT u.id, u.username, u.total_xp, u.avatar_url,
                   (SELECT COUNT(*) FROM unlocked_stages WHERE user_id = u.id) as stages_completed
            FROM users u
            WHERE u.role = 'student'
            ORDER BY u.total_xp DESC
            LIMIT :limit
        ");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
