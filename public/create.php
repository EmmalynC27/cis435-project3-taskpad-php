<?php
// Start session for flash messages and CSRF
session_start();

// Include required files
require_once '../src/storage.php';
require_once '../src/validation.php';
require_once '../src/csrf.php';
require_once '../src/flash.php';

// Initialize variables
$errors = [];
$formData = [];
$storage = new TaskStorage();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRFProtection::validateFromPost($_POST)) {
        FlashMessages::error('Invalid request. Please try again.');
        header('Location: create.php');
        exit;
    }
    
    // Validate form data
    [$isValid, $errors, $sanitized] = TaskValidator::validateCreate($_POST);
    
    if ($isValid) {
        // Save task
        $taskId = $storage->addTask($sanitized);
        
        if ($taskId) {
            FlashMessages::success('This task was created successfully!');
            header('Location: index.php');
            exit;
        } else {
            FlashMessages::error('Error: Failed to create task. Please try again.');
        }
    } else {
        // Preserve form data for re-display
        $formData = $_POST;
    }
}

// Generate CSRF token
$csrfToken = CSRFProtection::generateToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskPad PHP - Create Task</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>TaskPad PHP</h1>
            <nav>
                <a href="index.php" class="btn btn-secondary">Back to Tasks</a>
            </nav>
        </header>

        <main>
            <?php echo FlashMessages::display(); ?>

            <div class="form-container">
                <h2>Welcome to Task Manager!</h2>
                <p> Create and manage your current ongoing tasks easily and efficiently! </p>
                <form method="POST" action="create.php" class="task-form">
                    <?php echo CSRFProtection::getTokenField(); ?>
                    
                    <div class="form-group">
                        <label for="title">Task Master </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="<?php echo htmlspecialchars($formData['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                               class="<?php echo isset($errors['title']) ? 'error' : ''; ?>"
                               placeholder="Enter task title"
                               required>
                        <?php if (isset($errors['title'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="<?php echo isset($errors['description']) ? 'error' : ''; ?>"
                                  placeholder="Enter task description (optional)"
                                  rows="4"><?php echo htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="priority">Priority *</label>
                            <select id="priority" 
                                    name="priority" 
                                    class="<?php echo isset($errors['priority']) ? 'error' : ''; ?>"
                                    required>
                                <option value="">Select priority</option>
                                <option value="Low" <?php echo ($formData['priority'] ?? '') === 'Low' ? 'selected' : ''; ?>>Low</option>
                                <option value="Medium" <?php echo ($formData['priority'] ?? '') === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                <option value="High" <?php echo ($formData['priority'] ?? '') === 'High' ? 'selected' : ''; ?>>High</option>
                            </select>
                            <?php if (isset($errors['priority'])): ?>
                                <span class="error-message"><?php echo htmlspecialchars($errors['priority'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="due">Due Date</label>
                            <input type="date" 
                                   id="due" 
                                   name="due" 
                                   value="<?php echo htmlspecialchars($formData['due'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   class="<?php echo isset($errors['due']) ? 'error' : ''; ?>"
                                   min="<?php echo date('Y-m-d'); ?>">
                            <?php if (isset($errors['due'])): ?>
                                <span class="error-message"><?php echo htmlspecialchars($errors['due'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Task</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>