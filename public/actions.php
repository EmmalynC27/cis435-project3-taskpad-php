<?php
/**
 * TaskPad - Actions Handler
 * Processes form submissions (create, delete, toggle)
 */

require_once __DIR__ . '/../src/storage.php';
require_once __DIR__ . '/../src/validation.php';
require_once __DIR__ . '/../src/csrf.php';
require_once __DIR__ . '/../src/flash.php';

// Start session
session_start();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
    setFlash('error', 'Invalid security token. Please try again.');
    header('Location: index.php');
    exit;
}

// Get action
$action = $_POST['action'] ?? '';

// Handle different actions
switch ($action) {
    case 'create':
        handleCreate();
        break;
    
    case 'delete':
        handleDelete();
        break;
    
    case 'toggle':
        handleToggle();
        break;
    
    default:
        setFlash('error', 'Invalid action.');
        header('Location: index.php');
        exit;
}

/**
 * Handle task creation
 */
function handleCreate() {
    // Validate input
    $validation = validateTask($_POST);
    
    if (!$validation['valid']) {
        // Store errors and form data in session
        $_SESSION['form_errors'] = $validation['errors'];
        $_SESSION['form_data'] = $_POST;
        
        setFlash('error', 'Please correct the errors below.');
        header('Location: create.php');
        exit;
    }
    
    // Sanitize and create task
    $task = sanitizeTask($_POST);
    
    if (addTask($task)) {
        setFlash('success', 'Task created successfully!');
        header('Location: index.php');
    } else {
        setFlash('error', 'Failed to create task. Please try again.');
        header('Location: create.php');
    }
    exit;
}

/**
 * Handle task deletion
 */
function handleDelete() {
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        setFlash('error', 'Invalid task ID.');
        header('Location: index.php');
        exit;
    }
    
    if (deleteTask($id)) {
        setFlash('success', 'Task deleted successfully!');
    } else {
        setFlash('error', 'Failed to delete task.');
    }
    
    header('Location: index.php');
    exit;
}

/**
 * Handle task completion toggle
 */
function handleToggle() {
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        setFlash('error', 'Invalid task ID.');
        header('Location: index.php');
        exit;
    }
    
    if (toggleTaskComplete($id)) {
        setFlash('success', 'Task status updated!');
    } else {
        setFlash('error', 'Failed to update task status.');
    }
    
    header('Location: index.php');
    exit;
}
