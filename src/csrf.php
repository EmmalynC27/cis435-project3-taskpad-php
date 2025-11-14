<?php

class CSRFProtection {
    /**
     * Generate a CSRF token and store it in session
     * @return string
     */
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    /**
     * Verify CSRF token
     * @param string $token
     * @return bool
     */
    public static function verifyToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Get the current CSRF token
     * @return string|null
     */
    public static function getToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['csrf_token'] ?? null;
    }

    /**
     * Generate HTML input field for CSRF token
     * @return string
     */
    public static function getTokenField() {
        $token = self::getToken() ?: self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Validate CSRF token from POST data
     * @param array $postData
     * @return bool
     */
    public static function validateFromPost($postData) {
        $token = $postData['csrf_token'] ?? '';
        return self::verifyToken($token);
    }
}