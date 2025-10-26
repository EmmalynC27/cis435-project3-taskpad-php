<?php
/**
 * TaskPad - Create Task Page
 * Form for creating new tasks
 */

require_once __DIR__ . '/../src/csrf.php';
require_once __DIR__ . '/../src/flash.php';

// Get any error data from session
session_start();
$errors = $_SESSION['form_errors'] ?? [];
$oldData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task - TaskPad</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 TaskPad</h1>
            <p class="subtitle">Create a new task</p>
        </header>

        <?php echo displayFlash(); ?>

        <div class="actions">
            <a href="index.php" class="btn btn-secondary">← Back to Tasks</a>
        </div>

        <div class="form-container">
            <form method="POST" action="actions.php" class="task-form">
                <?php echo csrfTokenField(); ?>
                <input type="hidden" name="action" value="create">

                <div class="form-group">
                    <label for="title">Task Title *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        class="form-control <?php echo isset($errors['title']) ? 'error' : ''; ?>"
                        value="<?php echo htmlspecialchars($oldData['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="Enter task title"
                        required
                    >
                    <?php if (isset($errors['title'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control <?php echo isset($errors['description']) ? 'error' : ''; ?>"
                        rows="4"
                        placeholder="Enter task description (optional)"
                    ><?php echo htmlspecialchars($oldData['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-control">
                        <option value="low" <?php echo ($oldData['priority'] ?? 'medium') === 'low' ? 'selected' : ''; ?>>
                            Low
                        </option>
                        <option value="medium" <?php echo ($oldData['priority'] ?? 'medium') === 'medium' ? 'selected' : ''; ?>>
                            Medium
                        </option>
                        <option value="high" <?php echo ($oldData['priority'] ?? 'medium') === 'high' ? 'selected' : ''; ?>>
                            High
                        </option>
                    </select>
                    <?php if (isset($errors['priority'])): ?>
                        <span class="error-message"><?php echo htmlspecialchars($errors['priority'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create Task</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <footer>
            <p>TaskPad &copy; <?php echo date('Y'); ?> | CIS 435 Project 3</p>
        </footer>
    </div>
</body>
</html>
