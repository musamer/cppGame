<?php
/*
 * Session Helper
 * Wrapper for session manipulation and flash messaging
 */
class Session
{

    // Set session key
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // Get session key
    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    // Delete session key
    public static function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Destroy whole session
    public static function destroy()
    {
        session_destroy();
    }

    // Flash messaging
    // Set: Session::flash('register_success', 'You are now registered');
    // Display: Session::flash('register_success');
    public static function flash($name = '', $message = '', $class = 'alert alert-success')
    {
        if (!empty($name)) {
            // Set message
            if (!empty($message) && empty($_SESSION[$name])) {
                if (!empty($_SESSION[$name . '_class'])) {
                    unset($_SESSION[$name . '_class']);
                }
                $_SESSION[$name] = $message;
                $_SESSION[$name . '_class'] = $class;
            }
            // Display message
            elseif (empty($message) && !empty($_SESSION[$name])) {
                $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
                echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
                unset($_SESSION[$name]);
            }
        }
    }

    public static function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
}
