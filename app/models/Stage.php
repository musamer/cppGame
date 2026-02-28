<?php
class Stage extends Model
{
    public function getStageById($id)
    {
        $this->db->query('SELECT * FROM stages WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllStagesWithWorlds()
    {
        $this->db->query('
            SELECT s.*, w.title as world_title 
            FROM stages s 
            LEFT JOIN worlds w ON s.world_id = w.id 
            ORDER BY w.order_index ASC, s.order_index ASC
        ');
        return $this->db->resultSet();
    }

    public function updateStage($data)
    {
        $this->db->query('
            UPDATE stages 
            SET title = :title, 
                description = :description, 
                world_id = :world_id, 
                order_index = :order_index, 
                xp_reward = :xp_reward 
            WHERE id = :id
        ');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':world_id', $data['world_id']);
        $this->db->bind(':order_index', $data['order_index']);
        $this->db->bind(':xp_reward', $data['xp_reward']);

        return $this->db->execute();
    }

    public function getNextStage($worldId, $orderIndex)
    {
        // 1. Try to find next stage in the SAME world
        $this->db->query('SELECT id FROM stages WHERE world_id = :wid AND order_index > :idx ORDER BY order_index ASC LIMIT 1');
        $this->db->bind(':wid', $worldId);
        $this->db->bind(':idx', $orderIndex);
        $next = $this->db->single();

        if ($next) return $next->id;

        // 2. If no more stages in current world, find first stage of the NEXT world
        // Get current world order index
        $this->db->query('SELECT order_index FROM worlds WHERE id = :wid');
        $this->db->bind(':wid', $worldId);
        $currentWorldOrder = $this->db->single()->order_index;

        $this->db->query('SELECT id FROM worlds WHERE order_index > :curr_idx ORDER BY order_index ASC LIMIT 1');
        $this->db->bind(':curr_idx', $currentWorldOrder);
        $nextWorld = $this->db->single();

        if ($nextWorld) {
            $this->db->query('SELECT id FROM stages WHERE world_id = :nwid ORDER BY order_index ASC LIMIT 1');
            $this->db->bind(':nwid', $nextWorld->id);
            $firstStageNextWorld = $this->db->single();
            return $firstStageNextWorld ? $firstStageNextWorld->id : null;
        }

        return null;
    }
}
