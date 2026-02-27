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
        $this->db->query('SELECT * FROM exercises WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
