<?php

class FlashMessages {
    /**
     * Initialize session if needed
     */
    private static function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a flash message
     * @param string $type (success, error, warning, info)
     * @param string $message
     */
    public static function set($type, $message) {
        self::initSession();

        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }

        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Set a success message
     * @param string $message
     */
    public static function success($message) {
        self::set('success', $message);
    }

    /**
     * Set an error message
     * @param string $message
     */
    public static function error($message) {
        self::set('error', $message);
    }

    /**
     * Set a warning message
     * @param string $message
     */
    public static function warning($message) {
        self::set('warning', $message);
    }

    /**
     * Set an info message
     * @param string $message
     */
    public static function info($message) {
        self::set('info', $message);
    }

    /**
     * Get all flash messages and clear them
     * @return array
     */
    public static function getAndClear() {
        self::initSession();

        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);

        return $messages;
    }

    /**
     * Check if there are any flash messages
     * @return bool
     */
    public static function hasMessages() {
        self::initSession();
        return !empty($_SESSION['flash_messages']);
    }

    /**
     * Display flash messages as HTML
     * @return string
     */
    public static function display() {
        $messages = self::getAndClear();

        if (empty($messages)) {
            return '';
        }

        $html = '';
        foreach ($messages as $message) {
            $type = htmlspecialchars($message['type'], ENT_QUOTES, 'UTF-8');
            $text = htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8');

            $html .= '<div class="alert alert-' . $type . '" role="alert">';
            $html .= '<button type="button" class="close" onclick="this.parentElement.style.display=\'none\'">';
            $html .= '<span>&times;</span>';
            $html .= '</button>';
            $html .= $text;
            $html .= '</div>';
        }

        return $html;
    }
}