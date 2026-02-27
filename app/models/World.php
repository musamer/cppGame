<?php
class World extends Model
{

    // Get all active worlds with their stages
    public function getWorldsWithStages()
    {
        $this->db->query('SELECT * FROM worlds WHERE is_active = 1 ORDER BY order_index ASC');
        $worlds = $this->db->resultSet();

        foreach ($worlds as $world) {
            $this->db->query('SELECT * FROM stages WHERE world_id = :world_id ORDER BY order_index ASC');
            $this->db->bind(':world_id', $world->id);
            // I'm using array cast to be compatible with views easily if preferred, but objects are fine.
            $world->stages = $this->db->resultSet();
        }

        return $worlds;
    }
}
