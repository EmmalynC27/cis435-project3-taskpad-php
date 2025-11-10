<?php
// Simple test to verify the core classes work
require_once '../src/storage.php';
require_once '../src/validation.php';

echo "Testing TaskPad PHP Core Functionality\n";
echo "=====================================\n\n";

// Test storage
echo "1. Testing TaskStorage...\n";
$storage = new TaskStorage('../data/tasks.json');
$tasks = $storage->getAllTasks();
echo "   - Loaded " . count($tasks) . " existing tasks\n";

// Test validation
echo "\n2. Testing TaskValidator...\n";
$testData = [
    'title' => 'Test Task',
    'description' => 'This is a test task',
    'priority' => 'Medium',
    'due' => '2025-12-01'
];

[$isValid, $errors, $sanitized] = TaskValidator::validateCreate($testData);
echo "   - Valid task validation: " . ($isValid ? "PASS" : "FAIL") . "\n";

$invalidData = [
    'title' => '',
    'description' => '',
    'priority' => 'Invalid',
    'due' => 'bad-date'
];

[$isValid2, $errors2, $sanitized2] = TaskValidator::validateCreate($invalidData);
echo "   - Invalid task validation: " . (!$isValid2 ? "PASS" : "FAIL") . "\n";
echo "   - Error count: " . count($errors2) . "\n";

echo "\n3. Testing task creation...\n";
$taskId = $storage->addTask($sanitized);
if ($taskId) {
    echo "   - Task created with ID: $taskId\n";
    
    // Test retrieval
    $retrievedTask = $storage->getTask($taskId);
    echo "   - Task retrieved: " . ($retrievedTask ? "PASS" : "FAIL") . "\n";
    
    // Test completion
    $completed = $storage->completeTask($taskId);
    echo "   - Task completed: " . ($completed ? "PASS" : "FAIL") . "\n";
    
    // Test deletion
    $deleted = $storage->deleteTask($taskId);
    echo "   - Task deleted: " . ($deleted ? "PASS" : "FAIL") . "\n";
} else {
    echo "   - Task creation FAILED\n";
}

echo "\nCore functionality test completed!\n";