<?php
/**
 * CSRF Protection Module
 */

/**
 * Start session if not already started
 */
function ensureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Generate a CSRF token
 * @return string CSRF token
 */
function generateCsrfToken() {
    ensureSession();
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Get the current CSRF token
 * @return string|null CSRF token or null if not set
 */
function getCsrfToken() {
    ensureSession();
    return $_SESSION['csrf_token'] ?? null;
}

/**
 * Validate CSRF token
 * @param string $token Token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token) {
    $sessionToken = getCsrfToken();
    
    if ($sessionToken === null || $token === null) {
        return false;
    }
    
    return hash_equals($sessionToken, $token);
}

/**
 * Generate HTML for CSRF token hidden input
 * @return string HTML input field
 */
function csrfTokenField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}
