<?php
session_start();

require_once '../src/storage.php';
require_once '../src/validation.php';
require_once '../src/csrf.php';
require_once '../src/flash.php';


// POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    FlashMessages::error('Method not allowed.');
    header('Location: index.php');
    exit;
}

// Verify CSRF token
if (!CSRFProtection::validateFromPost($_POST)) {
    FlashMessages::error('Invalid request. Please try again.');
    header('Location: index.php');
    exit;
}

// Initialize storage
$storage = new TaskStorage();

// Get and validate action
$action = $_POST['action'] ?? '';
$taskId = $_POST['task_id'] ?? '';

// Validate task ID
if (!TaskValidator::validateTaskId($taskId)) {
    FlashMessages::error('Invalid task ID.');
    header('Location: index.php');
    exit;
}

// Check if task exists
$task = $storage->getTask($taskId);
if (!$task) {
    FlashMessages::error('Task not found.');
    header('Location: index.php');
    exit;
}

// Handle different actions
switch ($action) {
    case 'complete':
        if ($task['completed']) {
            FlashMessages::warning('Task is already completed.');
        } else {
            if ($storage->completeTask($taskId)) {
                FlashMessages::success('Task marked as completed!');
            } else {
                FlashMessages::error('Failed to complete task. Please try again.');
            }
        }
        break;

    case 'delete':
        if ($storage->deleteTask($taskId)) {
            FlashMessages::success('Task deleted successfully!');
        } else {
            FlashMessages::error('Failed to delete task. Please try again.');
        }
        break;

    default:
        FlashMessages::error('Invalid action.');
        break;
}

// Redirect back to task list
header('Location: index.php');
exit;