<?php
class Stage extends Model
{
    public function getStageById($id)
    {
        $this->db->query('SELECT * FROM stages WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
