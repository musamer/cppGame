<?php
class World extends Model
{

    // Get all active worlds with their stages and lock status for a specific user
    public function getWorldsWithStages($userId)
    {
        $this->db->query('SELECT * FROM worlds WHERE is_active = 1 ORDER BY order_index ASC');
        $worlds = $this->db->resultSet();

        // fetch unlocked worlds
        $this->db->query('SELECT world_id FROM unlocked_worlds WHERE user_id = :uid');
        $this->db->bind(':uid', $userId);
        $unlockedWorldsDb = $this->db->resultSet();
        $unlockedWorldIds = [];
        foreach ($unlockedWorldsDb as $uw) {
            $unlockedWorldIds[] = $uw->world_id;
        }

        // fetch unlocked stages
        $this->db->query('SELECT stage_id FROM unlocked_stages WHERE user_id = :uid');
        $this->db->bind(':uid', $userId);
        $unlockedStagesDb = $this->db->resultSet();
        $unlockedStageIds = [];
        foreach ($unlockedStagesDb as $us) {
            $unlockedStageIds[] = $us->stage_id;
        }

        // --- Fallback for existing users or bugged states: World 1 and First Stage MUST be unlocked ---
        // Fetch the IDs dynamically to avoid hardcoding errors after restructuring
        $this->db->query('SELECT id FROM worlds ORDER BY order_index ASC LIMIT 1');
        $firstWorld = $this->db->single();
        $this->db->query('SELECT id FROM stages WHERE world_id = :wid ORDER BY order_index ASC LIMIT 1');
        $this->db->bind(':wid', $firstWorld->id);
        $firstStage = $this->db->single();

        if ($firstWorld && !in_array($firstWorld->id, $unlockedWorldIds)) {
            $unlockedWorldIds[] = $firstWorld->id;
        }
        if ($firstStage && !in_array($firstStage->id, $unlockedStageIds)) {
            $unlockedStageIds[] = $firstStage->id;
        }

        // fetch max score per stage to determine "solved" state
        $this->db->query('
            SELECT e.stage_id, MAX(s.score) as max_score 
            FROM submissions s 
            JOIN exercises e ON s.exercise_id = e.id 
            WHERE s.user_id = :uid 
            GROUP BY e.stage_id
        ');
        $this->db->bind(':uid', $userId);
        $scoresDb = $this->db->resultSet();
        $stageScores = [];
        foreach ($scoresDb as $row) {
            $stageScores[$row->stage_id] = (int)$row->max_score;
        }

        foreach ($worlds as $world) {
            // Check if world is locked for this user
            $world->locked = !in_array($world->id, $unlockedWorldIds);

            // Fetch stages for this world
            $this->db->query('SELECT * FROM stages WHERE world_id = :world_id ORDER BY order_index ASC');
            $this->db->bind(':world_id', $world->id);
            $stages = $this->db->resultSet();

            foreach ($stages as $stage) {
                // Determine if stage is locked
                $stage->locked = $world->locked || !in_array($stage->id, $unlockedStageIds);

                // Determine max score and solved status
                $stage->max_score = isset($stageScores[$stage->id]) ? $stageScores[$stage->id] : 0;
                $stage->is_solved = ($stage->max_score >= 100);
            }

            $world->stages = $stages;
        }

        return $worlds;
    }

    // Safely unlock a stage (and its corresponding world) for a user
    public function unlockStageForUser($userId, $stageId)
    {
        // Unlock the specific stage safely
        $this->db->query("INSERT IGNORE INTO unlocked_stages (user_id, stage_id) VALUES (:uid, :sid)");
        $this->db->bind(':uid', $userId);
        $this->db->bind(':sid', $stageId);
        $this->db->execute();

        // Unlock the world that stage belongs to
        $this->db->query("INSERT IGNORE INTO unlocked_worlds (user_id, world_id) SELECT :uid, world_id FROM stages WHERE id = :sid");
        $this->db->bind(':uid', $userId);
        $this->db->bind(':sid', $stageId);
        $this->db->execute();
    }

    // Get all worlds (for admin panel)
    public function getAllWorlds()
    {
        $this->db->query('SELECT * FROM worlds ORDER BY order_index ASC');
        return $this->db->resultSet();
    }
}
