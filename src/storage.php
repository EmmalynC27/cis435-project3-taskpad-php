<?php
/**
 * Storage Module - Handles task persistence using JSON
 */

/**
 * Get the path to the tasks.json file
 */
function getTasksFilePath() {
    return __DIR__ . '/../data/tasks.json';
}

/**
 * Load all tasks from storage
 * @return array Array of tasks
 */
function loadTasks() {
    $filePath = getTasksFilePath();
    
    if (!file_exists($filePath)) {
        return [];
    }
    
    $content = file_get_contents($filePath);
    $tasks = json_decode($content, true);
    
    return is_array($tasks) ? $tasks : [];
}

/**
 * Save tasks to storage
 * @param array $tasks Array of tasks to save
 * @return bool True on success, false on failure
 */
function saveTasks($tasks) {
    $filePath = getTasksFilePath();
    
    // Ensure data directory exists
    $dataDir = dirname($filePath);
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    $json = json_encode($tasks, JSON_PRETTY_PRINT);
    return file_put_contents($filePath, $json) !== false;
}

/**
 * Add a new task
 * @param array $task Task data
 * @return bool True on success, false on failure
 */
function addTask($task) {
    $tasks = loadTasks();
    
    // Generate unique ID
    $task['id'] = uniqid('task_', true);
    $task['created_at'] = date('Y-m-d H:i:s');
    
    $tasks[] = $task;
    
    return saveTasks($tasks);
}

/**
 * Delete a task by ID
 * @param string $id Task ID
 * @return bool True on success, false on failure
 */
function deleteTask($id) {
    $tasks = loadTasks();
    
    $filteredTasks = array_filter($tasks, function($task) use ($id) {
        return $task['id'] !== $id;
    });
    
    // Re-index array
    $filteredTasks = array_values($filteredTasks);
    
    return saveTasks($filteredTasks);
}

/**
 * Toggle task completion status
 * @param string $id Task ID
 * @return bool True on success, false on failure
 */
function toggleTaskComplete($id) {
    $tasks = loadTasks();
    
    foreach ($tasks as &$task) {
        if ($task['id'] === $id) {
            $task['completed'] = !($task['completed'] ?? false);
            return saveTasks($tasks);
        }
    }
    
    return false;
}
