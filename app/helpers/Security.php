<?php
/*
 * Security Helper Class
 * Handles basic XSS sanitization and data filtering
 */
class Security
{

    // Sanitize string against XSS
    public static function sanitizeString($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    // Sanitize an array of POST/GET data
    public static function sanitizeArray($array)
    {
        $sanitized = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = static::sanitizeArray($value);
            } else {
                $sanitized[$key] = static::sanitizeString($value);
            }
        }
        return $sanitized;
    }
}
