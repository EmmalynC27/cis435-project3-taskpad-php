<?php
/**
 * TaskPad - Main Index Page
 * Displays list of all tasks
 */

require_once __DIR__ . '/../src/storage.php';
require_once __DIR__ . '/../src/flash.php';
require_once __DIR__ . '/../src/csrf.php';

// Load all tasks
$tasks = loadTasks();

// Sort tasks: incomplete first, then by priority
usort($tasks, function($a, $b) {
    // First sort by completion status
    $aComplete = $a['completed'] ?? false;
    $bComplete = $b['completed'] ?? false;
    
    if ($aComplete !== $bComplete) {
        return $aComplete ? 1 : -1;
    }
    
    // Then sort by priority
    $priorityOrder = ['high' => 0, 'medium' => 1, 'low' => 2];
    $aPriority = $priorityOrder[$a['priority'] ?? 'medium'];
    $bPriority = $priorityOrder[$b['priority'] ?? 'medium'];
    
    return $aPriority - $bPriority;
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskPad - Your Tasks</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 TaskPad</h1>
            <p class="subtitle">Manage your tasks efficiently</p>
        </header>

        <?php echo displayFlash(); ?>

        <div class="actions">
            <a href="create.php" class="btn btn-primary">+ New Task</a>
        </div>

        <?php if (empty($tasks)): ?>
            <div class="empty-state">
                <p>No tasks yet! Create your first task to get started.</p>
                <a href="create.php" class="btn btn-primary">Create Task</a>
            </div>
        <?php else: ?>
            <div class="task-list">
                <?php foreach ($tasks as $task): ?>
                    <?php
                        $completed = $task['completed'] ?? false;
                        $priority = $task['priority'] ?? 'medium';
                        $taskClass = $completed ? 'task completed' : 'task';
                        $taskClass .= ' priority-' . $priority;
                    ?>
                    <div class="<?php echo $taskClass; ?>">
                        <div class="task-header">
                            <div class="task-title-section">
                                <form method="POST" action="actions.php" class="inline-form">
                                    <?php echo csrfTokenField(); ?>
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="checkbox-btn" title="Toggle completion">
                                        <span class="checkbox <?php echo $completed ? 'checked' : ''; ?>">
                                            <?php echo $completed ? '✓' : ''; ?>
                                        </span>
                                    </button>
                                </form>
                                <h3><?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            </div>
                            <span class="priority-badge priority-<?php echo $priority; ?>">
                                <?php echo ucfirst($priority); ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($task['description'])): ?>
                            <p class="task-description"><?php echo nl2br(htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                        <?php endif; ?>
                        
                        <div class="task-footer">
                            <span class="task-date">
                                <?php 
                                    if (isset($task['created_at'])) {
                                        $date = new DateTime($task['created_at']);
                                        echo 'Created: ' . $date->format('M j, Y g:i A');
                                    }
                                ?>
                            </span>
                            <form method="POST" action="actions.php" class="inline-form">
                                <?php echo csrfTokenField(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Are you sure you want to delete this task?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <footer>
            <p>TaskPad &copy; <?php echo date('Y'); ?> | CIS 435 Project 3</p>
        </footer>
    </div>
</body>
</html>
