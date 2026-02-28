<?php
class StudentController extends Controller
{
    private $worldModel;
    private $stageModel;
    private $exerciseModel;
    private $gameEngine;
    private $aiEngine;
    private $submissionModel;
    private $friendModel;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            redirect('/auth/login');
        }

        // Instructors and admins are allowed to preview the student views, 
        // so we don't redirect them away from the StudentController anymore.

        $this->worldModel = $this->model('World');
        $this->stageModel = $this->model('Stage');
        $this->exerciseModel = $this->model('Exercise');
        $this->gameEngine = $this->model('GameEngine');
        $this->aiEngine = $this->model('AIEngine');
        $this->submissionModel = $this->model('Submission');
        $this->friendModel = $this->model('Friend');
    }

    public function dashboard()
    {
        // Get all worlds and stages from DB along with lock status for current user
        $worlds = $this->worldModel->getWorldsWithStages($_SESSION['user_id']);

        // Convert the object structure to array format the View expects
        $formattedWorlds = [];
        foreach ($worlds as $w) {
            $formattedStages = [];
            foreach ($w->stages as $s) {
                $formattedStages[] = [
                    'id' => $s->id,
                    'title' => $s->title,
                    'locked' => $s->locked,
                    'is_solved' => $s->is_solved,
                    'max_score' => $s->max_score,
                    'xp_reward' => $s->xp_reward
                ];
            }

            $formattedWorlds[] = [
                'id' => $w->id,
                'title' => $w->title,
                'locked' => $w->locked,
                'stages' => $formattedStages
            ];
        }


        $userModel = $this->model('User');
        $rankTitle = $userModel->getRankTitle($_SESSION['user_xp']);
        $nextRankInfo = $userModel->getNextRankInfo($_SESSION['user_xp']);

        $data = [
            'title' => 'لوحة الفارس',
            'worlds' => $formattedWorlds,
            'rank_title' => $rankTitle,
            'next_rank_info' => $nextRankInfo
        ];

        $this->view('layouts/header', $data);
        $this->view('student/dashboard', $data);
        $this->view('layouts/footer');
    }

    // view and manage friends
    public function friends()
    {
        // Handle POST form submission to add friend
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $friendCode = trim($_POST['friend_code']);

            if (empty($friendCode)) {
                Session::flash('error', 'يرجى إدخال رمز الصداقة.');
            } else {
                $status = $this->friendModel->addFriend($_SESSION['user_id'], $friendCode);

                if ($status === 'success') {
                    Session::flash('success', 'تمت إضافة الصديق بنجاح! ساحة المعركة تشتعل.');
                } else if ($status === 'already_friends') {
                    Session::flash('error', 'أنتم أصدقاء بالفعل!');
                } else if ($status === 'not_found') {
                    Session::flash('error', 'رمز الفارس غير صحيح أو لم يتم العثور عليه، أو تحاول إضافة نفسك.');
                } else {
                    Session::flash('error', 'حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.');
                }
            }
            redirect('/student/friends');
            return;
        }

        // GET Request -> fetch leaderboard data
        $leaderboard = $this->friendModel->getFriendsLeaderboard($_SESSION['user_id']);

        // Fetch current user details to show their own friend code
        $userModel = $this->model('User');
        $currentUser = $userModel->getUserById($_SESSION['user_id']);

        $data = [
            'title' => 'قائمة الفرسان الأصدقاء',
            'leaderboard' => $leaderboard,
            'my_code' => $currentUser->friend_code
        ];

        $this->view('layouts/header', $data);
        $this->view('student/friends', $data);
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

    // POST API endpoint
    public function submit_exercise()
    {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get raw POST data (from fetch API)
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $exerciseId = $data['exercise_id'] ?? 0;
            $code = $data['code'] ?? '';

            if (!$exerciseId || empty($code)) {
                echo json_encode(['error' => 'Invalid data submission.']);
                return;
            }

            // Get exercise details to know the XP reward
            $exercise = $this->exerciseModel->getExerciseById($exerciseId);
            if (!$exercise) {
                echo json_encode(['error' => 'Exercise not found.']);
                return;
            }

            // Using the Submission model to save the code properly
            $submissionId = $this->submissionModel->saveSubmission($_SESSION['user_id'], $exerciseId, $code, 'pending');

            // Build the accurate context
            $exerciseContext = "Exercise Title: " . $exercise->title . "\nProblem Description: " . $exercise->content . "\nTest Cases: " . $exercise->test_cases;

            // Evaluate with AI Engine
            $aiFeedback = $this->aiEngine->evaluateCode($submissionId, $code, $exerciseContext, 1);

            // Get previous max score to calculate partial XP
            $prevScore = (int)$this->submissionModel->getMaxScore($_SESSION['user_id'], $exerciseId);
            $newScore = (int)($aiFeedback['score'] ?? 0);

            $status = ($newScore >= 70) ? 'passed' : 'failed';

            // Update submission status
            $this->submissionModel->updateSubmissionScore($submissionId, $status, $newScore);

            $xpAwarded = 0;
            // Award Partial XP only if the new score is better than previous
            if ($newScore > $prevScore) {
                // Determine the score gain percentage
                $scoreDiff = $newScore - $prevScore;

                // Partial XP awarded is based on the score difference percentage (since max score is 100)
                $xpAwarded = (int)floor(($scoreDiff / 100) * $exercise->xp_reward);

                if ($xpAwarded > 0) {
                    $this->gameEngine->addXP($_SESSION['user_id'], $xpAwarded, 'exercise', $exerciseId);
                    $_SESSION['user_xp'] += $xpAwarded;
                }
            }

            // Advanced unlocking logic: if user solves/passes completely (100%), open the NEXT stage directly
            if ($newScore == 100) {
                // Get current stage details
                $currentStage = $this->stageModel->getStageById($exercise->stage_id);

                // Find next stage ID logically
                $nextStageId = $this->stageModel->getNextStage($currentStage->world_id, $currentStage->order_index);

                if ($nextStageId) {
                    // Unlock the next stage directly using the model method (avoids protected property errors)
                    $this->worldModel->unlockStageForUser($_SESSION['user_id'], $nextStageId);
                }
            }

            // Return JSON response to frontend
            $response = [
                'success' => true,
                'score' => $aiFeedback['score'],
                'general_feedback' => $aiFeedback['general_feedback'],
                'hint' => $aiFeedback['hint'],
                'status' => $status,
                'xp_awarded' => $xpAwarded,
                'new_total_xp' => $_SESSION['user_xp']
            ];

            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Invalid request method.']);
        }
    }
}
