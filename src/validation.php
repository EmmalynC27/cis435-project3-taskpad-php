<?php
/**
 * Validation Module - Input validation functions
 */

/**
 * Validate task data
 * @param array $data Task data to validate
 * @return array Array with 'valid' (bool) and 'errors' (array) keys
 */
function validateTask($data) {
    $errors = [];
    
    // Validate title
    if (!isset($data['title']) || trim($data['title']) === '') {
        $errors['title'] = 'Task title is required.';
    } elseif (strlen($data['title']) > 255) {
        $errors['title'] = 'Task title must not exceed 255 characters.';
    }
    
    // Validate description (optional, but check length if provided)
    if (isset($data['description']) && strlen($data['description']) > 1000) {
        $errors['description'] = 'Task description must not exceed 1000 characters.';
    }
    
    // Validate priority
    $validPriorities = ['low', 'medium', 'high'];
    if (isset($data['priority']) && !in_array($data['priority'], $validPriorities)) {
        $errors['priority'] = 'Invalid priority level.';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Sanitize task data
 * @param array $data Raw task data
 * @return array Sanitized task data
 */
function sanitizeTask($data) {
    return [
        'title' => htmlspecialchars(trim($data['title'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'description' => htmlspecialchars(trim($data['description'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'priority' => in_array($data['priority'] ?? 'medium', ['low', 'medium', 'high']) 
            ? $data['priority'] 
            : 'medium',
        'completed' => false
    ];
}
