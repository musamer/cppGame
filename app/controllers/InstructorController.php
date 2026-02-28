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
        // Load the instructor model for analytics
        $instructorModel = $this->model('Instructor');

        // Fetch real data from the database
        $stats = $instructorModel->getDashboardStats();
        $strugglingExercises = $instructorModel->getStrugglingExercises(5);
        $recentSubmissions = $instructorModel->getRecentSubmissions(10);
        $topStudents = $instructorModel->getTopStudents(5);

        $data = [
            'title' => 'لوحة أوامر المدرب (السينسي)',
            'stats' => $stats,
            'struggling_exercises' => $strugglingExercises,
            'recent_submissions' => $recentSubmissions,
            'top_students' => $topStudents
        ];

        $this->view('layouts/header', $data);
        $this->view('instructor/dashboard', $data);
        $this->view('layouts/footer');
    }
}
