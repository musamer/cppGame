<?php
class InstructorController extends Controller
{
    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            redirect('/auth/login');
        }

        if ($_SESSION['user_role'] !== 'instructor' && $_SESSION['user_role'] !== 'admin') {
            redirect('/student/dashboard');
        }
    }

    public function dashboard()
    {
        // Mock data for analytics
        $data = [
            'title' => 'Instructor Analytics Dashboard',
            'students_count' => 120,
            'average_score' => 78,
            'struggling_concepts' => [
                ['name' => 'Nested Loops', 'failure_rate' => '40%'],
                ['name' => 'Pointers', 'failure_rate' => '65%'],
                ['name' => 'Array Out of Bounds', 'failure_rate' => '30%']
            ],
            'recent_submissions' => [
                ['student' => 'Ahmed', 'exercise' => 'تحدي جمع الطاقات', 'score' => 80, 'status' => 'passed'],
                ['student' => 'Sara', 'exercise' => 'وحوش التكرار', 'score' => 55, 'status' => 'failed']
            ]
        ];

        $this->view('layouts/header', $data);
        $this->view('instructor/dashboard', $data);
        $this->view('layouts/footer');
    }
}
