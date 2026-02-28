<?php
class AdminController extends Controller
{
    private $worldModel;
    private $stageModel;
    private $exerciseModel;
    private $db;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            redirect('/auth/login');
        }

        // Only explicitly set admin users, or instructors if allowed (for now treating instructor/admin as same for content management)
        if ($_SESSION['user_role'] !== 'instructor' && $_SESSION['user_role'] !== 'admin') {
            redirect('/student/dashboard');
        }

        $this->worldModel = $this->model('World');
        $this->stageModel = $this->model('Stage');
        $this->exerciseModel = $this->model('Exercise');
        $this->db = new Database();
    }

    // Admin Dashboard
    public function index()
    {
        $data = [
            'title' => 'لوحة التحكم للمشرف',
        ];

        $this->view('layouts/header', $data);
        $this->view('admin/index', $data);
        $this->view('layouts/footer');
    }

    // Reset All Student Progress (Destructive)
    public function reset_progress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_reset'])) {
            // 1. Truncate Progress Tables
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->execute();

            $tables = ['submissions', 'xp_logs', 'unlocked_stages', 'unlocked_worlds', 'user_badges', 'user_titles', 'spaced_reinforcement', 'ai_feedback'];
            foreach ($tables as $table) {
                $this->db->query("TRUNCATE TABLE $table");
                $this->db->execute();
            }

            // 2. Reset User Stats (Keep username/email/password)
            $this->db->query("UPDATE users SET total_xp = 0, current_level = 1");
            $this->db->execute();

            // 3. Re-unlock first stage for all users
            $this->db->query('SELECT id FROM worlds ORDER BY order_index ASC LIMIT 1');
            $firstWorldId = $this->db->single()->id;
            $this->db->query('SELECT id FROM stages WHERE world_id = :wid ORDER BY order_index ASC LIMIT 1');
            $this->db->bind(':wid', $firstWorldId);
            $firstStageId = $this->db->single()->id;

            $this->db->query("INSERT INTO unlocked_worlds (user_id, world_id) SELECT id, :wid FROM users");
            $this->db->bind(':wid', $firstWorldId);
            $this->db->execute();

            $this->db->query("INSERT INTO unlocked_stages (user_id, stage_id) SELECT id, :sid FROM users");
            $this->db->bind(':sid', $firstStageId);
            $this->db->execute();

            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
            $this->db->execute();

            Session::flash('success', 'تم تصفير كافة بيانات الطلاب بنجاح (مع الإبقاء على الحسابات).');
            redirect('/admin/index');
        } else {
            redirect('/admin/index');
        }
    }

    // List all stages to manage
    public function stages()
    {
        $worlds = $this->worldModel->getAllWorlds(); // Needs implementing
        $stages = $this->stageModel->getAllStagesWithWorlds();

        $data = [
            'title' => 'إدارة المراحل',
            'worlds' => $worlds,
            'stages' => $stages
        ];

        $this->view('layouts/header', $data);
        $this->view('admin/stages', $data);
        $this->view('layouts/footer');
    }

    // Toggle a stage active/inactive state (example control action)
    public function edit_stage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'world_id' => $_POST['world_id'],
                'order_index' => $_POST['order_index'],
                'xp_reward' => $_POST['xp_reward']
            ];

            if ($this->stageModel->updateStage($data)) {
                Session::flash('success', 'تم تحديث المرحلة بنجاح!');
            } else {
                Session::flash('error', 'حدث خطأ أثناء التحديث.');
            }
            redirect('/admin/stages');
        } else {
            // Display an edit form (simplified via modal normally, or separate page)
            $stage = $this->stageModel->getStageById($id);
            $worlds = $this->worldModel->getAllWorlds();

            $data = [
                'title' => 'تعديل المرحلة',
                'stage' => $stage,
                'worlds' => $worlds
            ];

            $this->view('layouts/header', $data);
            $this->view('admin/edit_stage', $data);
            $this->view('layouts/footer');
        }
    }

    // List all exercises
    public function exercises()
    {
        $exercises = $this->exerciseModel->getAllExercisesWithDetails();

        $data = [
            'title' => 'إدارة التحديات (الأسئلة)',
            'exercises' => $exercises
        ];

        $this->view('layouts/header', $data);
        $this->view('admin/exercises', $data);
        $this->view('layouts/footer');
    }

    // Edit an exercise
    public function edit_exercise($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'starter_code' => trim($_POST['starter_code']),
                'test_cases' => trim($_POST['test_cases']),
                'solution_code' => trim($_POST['solution_code']),
                'xp_reward' => $_POST['xp_reward']
            ];

            if ($this->exerciseModel->updateExercise($data)) {
                Session::flash('success', 'تم تحديث التحدي بنجاح!');
            } else {
                Session::flash('error', 'حدث خطأ أثناء التحديث.');
            }
            redirect('/admin/exercises');
        } else {
            $exercise = $this->exerciseModel->getExerciseById($id);

            $data = [
                'title' => 'تعديل التحدي',
                'exercise' => $exercise
            ];

            $this->view('layouts/header', $data);
            $this->view('admin/edit_exercise', $data);
            $this->view('layouts/footer');
        }
    }
}
