<?php
// Start session for flash messages and CSRF
session_start();

// Include required files
require_once '../src/storage.php';
require_once '../src/validation.php';
require_once '../src/csrf.php';
require_once '../src/flash.php';

// Initialize storage
$storage = new TaskStorage();

// Validate and sanitize filters
[$isValid, $filterErrors, $filters] = TaskValidator::validateFilters($_GET);

// Get filtered tasks
$tasks = $storage->filterTasks($filters);

// Count totals
$totalTasks = count($storage->getAllTasks());
$completedTasks = count(array_filter($storage->getAllTasks(), function($task) {
    return $task['completed'];
}));
$pendingTasks = $totalTasks - $completedTasks;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskPad PHP - Task List</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>TaskPad PHP</h1>
            <nav>
                <a href="index.php" class="btn btn-secondary">All Tasks</a>
                <a href="create.php" class="btn btn-primary">Add New Task</a>
            </nav>
        </header>

        <main>
            <?php echo FlashMessages::display(); ?>

            <!-- Task Statistics -->
            <div class="stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalTasks; ?></span>
                    <span class="stat-label">Total</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $pendingTasks; ?></span>
                    <span class="stat-label">Pending</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $completedTasks; ?></span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="q">Search:</label>
                        <input type="text" id="q" name="q" placeholder="Search tasks..." 
                               value="<?php echo htmlspecialchars($filters['q'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority:</label>
                        <select id="priority" name="priority">
                            <option value="">All Priorities</option>
                            <option value="Low" <?php echo ($filters['priority'] ?? '') === 'Low' ? 'selected' : ''; ?>>Low</option>
                            <option value="Medium" <?php echo ($filters['priority'] ?? '') === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="High" <?php echo ($filters['priority'] ?? '') === 'High' ? 'selected' : ''; ?>>High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="completed">Status:</label>
                        <select id="completed" name="completed">
                            <option value="">All Tasks</option>
                            <option value="false" <?php echo ($filters['completed'] ?? '') === 'false' ? 'selected' : ''; ?>>Pending</option>
                            <option value="true" <?php echo ($filters['completed'] ?? '') === 'true' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="index.php" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>

            <!-- Tasks List -->
            <div class="tasks-section">
                <?php if (empty($tasks)): ?>
                    <div class="empty-state">
                        <?php if (empty($storage->getAllTasks())): ?>
                            <h3>No current tasks -- add one now!</h3>
                            <p>Start by creating a new task! </p>
                            <a href="create.php" class="btn btn-primary">Create a Task</a>
                        <?php else: ?>
                            <h3>No tasks detected -- check your criteria!</h3>
                            <p>Verify your search criteria and filters. Please try again. </p>
                            <a href="index.php" class="btn btn-secondary">Display All Tasks</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="tasks-grid">
                        <?php foreach ($tasks as $task): ?>
                            <div class="task-card <?php echo $task['completed'] ? 'completed' : ''; ?>">
                                <div class="task-header">
                                    <h3 class="task-title"><?php echo TaskValidator::sanitizeOutput($task['title']); ?></h3>
                                    <span class="priority-badge priority-<?php echo strtolower($task['priority']); ?>">
                                        <?php echo $task['priority']; ?>
                                    </span>
                                </div>
                                
                                <?php if (!empty($task['description'])): ?>
                                    <p class="task-description"><?php echo TaskValidator::sanitizeOutput($task['description']); ?></p>
                                <?php endif; ?>
                                
                                <div class="task-meta">
                                    <?php if ($task['due']): ?>
                                        <span class="due-date">
                                            📅 <?php echo date('M j, Y', strtotime($task['due'])); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="created-date">
                                        Created: <?php echo date('M j, Y', strtotime($task['created_at'])); ?>
                                    </span>
                                </div>
                                
                                <div class="task-actions">
                                    <?php if (!$task['completed']): ?>
                                        <form method="POST" action="actions.php" style="display: inline;">
                                            <?php echo CSRFProtection::getTokenField(); ?>
                                            <input type="hidden" name="action" value="complete">
                                            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="submit" class="btn btn-success btn-sm">✓ Complete</button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="actions.php" style="display: inline;">
                                        <?php echo CSRFProtection::getTokenField(); ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Do you want to delete this task?')">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>