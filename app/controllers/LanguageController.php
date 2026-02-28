<?php

class LanguageController extends Controller
{
    public function switch($lang)
    {
        if (in_array($lang, ['ar', 'en'])) {
            $_SESSION['lang'] = $lang;
        }

        // Redirect back to referring page or dashboard
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('/student/dashboard');
        }
        exit;
    }
}
