<?php

class TaskStorage {
    private $dataFile;
    
    public function __construct($dataFile = '../data/tasks.json') {
        $this->dataFile = $dataFile;
        $this->initializeDataFile();
    }
    
    /**
     * Initialize the data file if it doesn't exist
     */
    private function initializeDataFile() {
        $dataDir = dirname($this->dataFile);
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
        
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([], JSON_PRETTY_PRINT));
        }
    }
    
    /**
     * Load all tasks from JSON file
     * @return array
     */
    public function getAllTasks() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        
        $content = file_get_contents($this->dataFile);
        $tasks = json_decode($content, true);
        
        return is_array($tasks) ? $tasks : [];
    }
    
    /**
     * Save tasks to JSON file
     * @param array $tasks
     * @return bool
     */
    private function saveTasks($tasks) {
        return file_put_contents($this->dataFile, json_encode($tasks, JSON_PRETTY_PRINT)) !== false;
    }
    
    /**
     * Add a new task
     * @param array $taskData
     * @return string|false Task ID on success, false on failure
     */
    public function addTask($taskData) {
        $tasks = $this->getAllTasks();
        
        // Generate unique ID
        $id = uniqid('task_', true);
        
        $task = [
            'id' => $id,
            'title' => $taskData['title'],
            'description' => $taskData['description'] ?? '',
            'priority' => $taskData['priority'],
            'due' => $taskData['due'] ?? null,
            'completed' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $tasks[] = $task;
        
        return $this->saveTasks($tasks) ? $id : false;
    }
    
    /**
     * Get a single task by ID
     * @param string $id
     * @return array|null
     */
    public function getTask($id) {
        $tasks = $this->getAllTasks();
        
        foreach ($tasks as $task) {
            if ($task['id'] === $id) {
                return $task;
            }
        }
        
        return null;
    }
    
    /**
     * Update a task
     * @param string $id
     * @param array $updates
     * @return bool
     */
    public function updateTask($id, $updates) {
        $tasks = $this->getAllTasks();
        
        foreach ($tasks as $index => $task) {
            if ($task['id'] === $id) {
                $tasks[$index] = array_merge($task, $updates, ['updated_at' => date('Y-m-d H:i:s')]);
                return $this->saveTasks($tasks);
            }
        }
        
        return false;
    }
    
    /**
     * Delete a task
     * @param string $id
     * @return bool
     */
    public function deleteTask($id) {
        $tasks = $this->getAllTasks();
        
        foreach ($tasks as $index => $task) {
            if ($task['id'] === $id) {
                unset($tasks[$index]);
                // Re-index array
                $tasks = array_values($tasks);
                return $this->saveTasks($tasks);
            }
        }
        
        return false;
    }
    
    /**
     * Filter tasks based on criteria
     * @param array $filters
     * @return array
     */
    public function filterTasks($filters = []) {
        $tasks = $this->getAllTasks();
        
        if (empty($filters)) {
            return $tasks;
        }
        
        $filtered = [];
        
        foreach ($tasks as $task) {
            $include = true;
            
            // Text search in title and description
            if (!empty($filters['q'])) {
                $searchText = strtolower($filters['q']);
                $titleMatch = strpos(strtolower($task['title']), $searchText) !== false;
                $descMatch = strpos(strtolower($task['description']), $searchText) !== false;
                
                if (!$titleMatch && !$descMatch) {
                    $include = false;
                }
            }
            
            // Priority filter
            if (!empty($filters['priority']) && $task['priority'] !== $filters['priority']) {
                $include = false;
            }
            
            // Status filter
            if (isset($filters['completed'])) {
                if ($filters['completed'] === 'true' && !$task['completed']) {
                    $include = false;
                } elseif ($filters['completed'] === 'false' && $task['completed']) {
                    $include = false;
                }
            }
            
            if ($include) {
                $filtered[] = $task;
            }
        }
        
        return $filtered;
    }
    
    /**
     * Mark task as completed
     * @param string $id
     * @return bool
     */
    public function completeTask($id) {
        return $this->updateTask($id, ['completed' => true]);
    }
}