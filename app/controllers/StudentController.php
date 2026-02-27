<?php
class StudentController extends Controller
{
    private $worldModel;
    private $stageModel;
    private $exerciseModel;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            redirect('/auth/login');
        }

        // Only students should access this dashboard ideally
        if ($_SESSION['user_role'] === 'instructor' || $_SESSION['user_role'] === 'admin') {
            redirect('/instructor/dashboard');
        }

        $this->worldModel = $this->model('World');
        $this->stageModel = $this->model('Stage');
        $this->exerciseModel = $this->model('Exercise');
    }

    public function dashboard()
    {
        // Get all worlds and stages from DB
        $worlds = $this->worldModel->getWorldsWithStages();

        // Convert the object structure to array format the View expects
        // and calculate locked status (simplified for now based on index, but should use DB progress eventually)
        $formattedWorlds = [];
        foreach ($worlds as $w) {
            $formattedStages = [];
            foreach ($w->stages as $s) {
                // In a real app, you would check a "user_stages" table or XP to see if locked
                // For demonstration, Stage 1 (Day 1) is unlocked, others might be locked based on a simple logic
                $isLocked = false;

                $formattedStages[] = [
                    'id' => $s->id,
                    'title' => $s->title,
                    'locked' => $isLocked,
                    'xp_reward' => $s->xp_reward
                ];
            }

            $formattedWorlds[] = [
                'id' => $w->id,
                'title' => $w->title,
                'stages' => $formattedStages
            ];
        }

        $data = [
            'title' => 'لوحة النينجا',
            'worlds' => $formattedWorlds
        ];

        $this->view('layouts/header', $data);
        $this->view('student/dashboard', $data);
        $this->view('layouts/footer');
    }

    // the URL route is student/exercise/{worldId}/{stageId}
    public function exercise($worldId, $stageId)
    {
        // Get the exercises for this stage
        $exercises = $this->exerciseModel->getExercisesByStageId($stageId);

        // If no exercises found, maybe redirect back
        if (empty($exercises)) {
            Session::flash('error', 'لا يوجد تمارين في هذه المرحلة حالياً.');
            redirect('/student/dashboard');
        }

        // Just take the first exercise for the stage for now 
        // (A real game would show a list if multiple, or cycle through them)
        $currentExercise = $exercises[0];

        $data = [
            'title' => $currentExercise->title,
            'exercise' => [
                'id' => $currentExercise->id,
                'title' => $currentExercise->title,
                'description' => $currentExercise->content,
                'starter_code' => $currentExercise->starter_code
            ]
        ];

        $this->view('layouts/header', $data);
        $this->view('student/exercise', $data);
        // The IDE view doesn't use the standard footer well because of h-screen, but we can leave or omit it.
    }
}
