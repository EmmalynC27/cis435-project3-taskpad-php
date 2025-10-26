<?php
/**
 * Flash Message Module - For displaying one-time messages
 */

/**
 * Start session if not already started
 */
function ensureFlashSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Set a flash message
 * @param string $type Message type (success, error, info, warning)
 * @param string $message Message content
 */
function setFlash($type, $message) {
    ensureFlashSession();
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * @return array|null Flash message array or null if no message
 */
function getFlash() {
    ensureFlashSession();
    
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    
    return null;
}

/**
 * Check if flash message exists
 * @return bool True if flash message exists
 */
function hasFlash() {
    ensureFlashSession();
    return isset($_SESSION['flash']);
}

/**
 * Display flash message as HTML
 * @return string HTML for flash message
 */
function displayFlash() {
    $flash = getFlash();
    
    if ($flash === null) {
        return '';
    }
    
    $type = htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8');
    
    return '<div class="flash flash-' . $type . '">' . $message . '</div>';
}
