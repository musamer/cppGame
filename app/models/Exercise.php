<?php
class Exercise extends Model
{

    // Get all exercises for a given stage
    public function getExercisesByStageId($stage_id)
    {
        $this->db->query('SELECT * FROM exercises WHERE stage_id = :stage_id');
        $this->db->bind(':stage_id', $stage_id);
        return $this->db->resultSet();
    }

    // Get a specific exercise
    public function getExerciseById($id)
    {
        $this->db->query('
            SELECT e.*, s.world_id 
            FROM exercises e 
            JOIN stages s ON e.stage_id = s.id 
            WHERE e.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Admin - Get all exercises with stage details
    public function getAllExercisesWithDetails()
    {
        $this->db->query('
            SELECT e.*, s.title as stage_title, s.order_index as stage_order, w.title as world_title 
            FROM exercises e 
            JOIN stages s ON e.stage_id = s.id 
            JOIN worlds w ON s.world_id = w.id
            ORDER BY w.order_index ASC, s.order_index ASC, e.id ASC
        ');
        return $this->db->resultSet();
    }

    // Admin - Update an exercise
    public function updateExercise($data)
    {
        $this->db->query('
            UPDATE exercises 
            SET title = :title, 
                content = :content, 
                starter_code = :starter_code, 
                test_cases = :test_cases, 
                solution_code = :solution_code, 
                xp_reward = :xp_reward 
            WHERE id = :id
        ');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':starter_code', $data['starter_code']);
        $this->db->bind(':test_cases', $data['test_cases']);
        $this->db->bind(':solution_code', $data['solution_code']);
        $this->db->bind(':xp_reward', $data['xp_reward']);

        return $this->db->execute();
    }
}
