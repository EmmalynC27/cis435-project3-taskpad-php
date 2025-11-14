<?php

class TaskValidator {
    /**
     * Validate task creation data
     * @param array $data
     * @return array [isValid, errors, sanitized]
     */
    public static function validateCreate($data) {
        $errors = [];
        $sanitized = [];

        // Title validation (required)
        if (empty($data['title']) || empty(trim($data['title']))) {
            $errors['title'] = 'Title is required';
            $sanitized['title'] = '';
        } else {
            $title = trim($data['title']);
            if (strlen($title) > 255) {
                $errors['title'] = 'Title must be 255 characters or less';
            }
            $sanitized['title'] = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        }

        // Description validation (optional)
        $description = isset($data['description']) ? trim($data['description']) : '';
        if (strlen($description) > 1000) {
            $errors['description'] = 'Description must be 1000 characters or less';
        }
        $sanitized['description'] = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

        // Priority validation (required, must be from allowed values)
        $allowedPriorities = ['Low', 'Medium', 'High'];
        if (empty($data['priority']) || !in_array($data['priority'], $allowedPriorities)) {
            $errors['priority'] = 'Priority must be Low, Medium, or High';
            $sanitized['priority'] = 'Low'; // Default
        } else {
            $sanitized['priority'] = $data['priority'];
        }

        // Due date validation (optional, must be valid date format)
        $due = isset($data['due']) ? trim($data['due']) : '';
        if (!empty($due)) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $due)) {
                $errors['due'] = 'Due date must be in YYYY-MM-DD format';
            } else {
                // Check if it's a valid date
                $dateParts = explode('-', $due);
                if (!checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                    $errors['due'] = 'Please enter a valid date';
                } elseif (strtotime($due) < strtotime(date('Y-m-d'))) {
                    $errors['due'] = 'Due date cannot be in the past';
                }
            }
        }
        $sanitized['due'] = $due ?: null;

        $isValid = empty($errors);

        return [$isValid, $errors, $sanitized];
    }

    /**
     * Validate filter parameters
     * @param array $data
     * @return array [isValid, errors, sanitized]
     */
    public static function validateFilters($data) {
        $errors = [];
        $sanitized = [];

        // Search query (optional)
        $query = isset($data['q']) ? trim($data['q']) : '';
        if (strlen($query) > 100) {
            $errors['q'] = 'Search query must be 100 characters or less';
        }
        $sanitized['q'] = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');

        // Priority filter (optional)
        $allowedPriorities = ['Low', 'Medium', 'High'];
        $priority = isset($data['priority']) ? trim($data['priority']) : '';
        if (!empty($priority) && !in_array($priority, $allowedPriorities)) {
            $errors['priority'] = 'Invalid priority filter';
            $priority = '';
        }
        $sanitized['priority'] = $priority;

        // Status filter (optional)
        $completed = isset($data['completed']) ? trim($data['completed']) : '';
        if (!empty($completed) && !in_array($completed, ['true', 'false'])) {
            $errors['completed'] = 'Invalid status filter';
            $completed = '';
        }
        $sanitized['completed'] = $completed;

        $isValid = empty($errors);

        return [$isValid, $errors, $sanitized];
    }

    /**
     * Sanitize string for safe output
     * @param string $str
     * @return string
     */
    public static function sanitizeOutput($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate task ID
     * @param string $id
     * @return bool
     */
    public static function validateTaskId($id) {
        return !empty($id) && is_string($id) && strlen($id) <= 50;
    }
}