<?php

// Check if a language change is requested via GET parameter or session
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    if (in_array($lang, ['en', 'ar'])) {
        $_SESSION['lang'] = $lang;
    }
}

// Default language is Arabic
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar';
}

$current_lang = $_SESSION['lang'];

// Load the appropriate language file
$lang_file_path = dirname(__DIR__) . '/lang/' . $current_lang . '.php';

if (file_exists($lang_file_path)) {
    $GLOBALS['lang'] = require_once $lang_file_path;
} else {
    // Fallback empty array
    $GLOBALS['lang'] = [];
}

/**
 * Helper function to translate a key
 */
function translate($key)
{
    if (isset($GLOBALS['lang'][$key])) {
        return $GLOBALS['lang'][$key];
    }
    // Return key itself if translation not found
    return $key;
}

/**
 * Shorthand for translate
 */
function __($key)
{
    return translate($key);
}
