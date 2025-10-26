# TaskPad - PHP Task Management Application

A simple, secure, and responsive task management application built with PHP and JSON storage for CIS 435 Project 3.

## Features

- ✅ **Create, Read, Update, Delete (CRUD)** operations for tasks
- ✅ **Priority levels**: High, Medium, Low with visual color coding
- ✅ **Task completion tracking** with toggle functionality
- ✅ **CSRF protection** on all form submissions
- ✅ **Input validation** and XSS prevention
- ✅ **Flash messages** for user feedback
- ✅ **Fully responsive** design with mobile support
- ✅ **JSON-based storage** - no database required

## Project Structure

```
cis435-project3-taskpad-php/
├── public/                 # Web-accessible files
│   ├── index.php          # Main task list page
│   ├── create.php         # Task creation form
│   ├── actions.php        # Form submission handler
│   └── assets/
│       ├── style.css      # Responsive stylesheet
│       └── screenshots/   # Application screenshots
├── src/                   # Backend modules
│   ├── storage.php        # JSON file operations
│   ├── validation.php     # Input validation
│   ├── csrf.php          # CSRF protection
│   └── flash.php         # Flash messages
└── data/
    └── tasks.json        # Task data storage
```

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/EmmalynC27/cis435-project3-taskpad-php.git
   cd cis435-project3-taskpad-php
   ```

2. **Ensure PHP is installed** (PHP 7.4 or higher recommended)
   ```bash
   php --version
   ```

3. **Set permissions for data directory**
   ```bash
   chmod 755 data
   chmod 644 data/tasks.json
   ```

4. **Start the PHP development server**
   ```bash
   cd public
   php -S localhost:8000
   ```

5. **Open in browser**
   Navigate to: `http://localhost:8000`

## Usage

### Creating a Task
1. Click the "+ New Task" button
2. Enter task title (required)
3. Optionally add a description
4. Select priority level (Low, Medium, or High)
5. Click "Create Task"

### Managing Tasks
- **Complete a task**: Click the checkbox next to the task
- **Delete a task**: Click the "Delete" button (with confirmation)
- Tasks are automatically sorted by completion status and priority

## Technical Details

### Backend Components

**storage.php**
- Handles all JSON file operations
- Functions: `loadTasks()`, `saveTasks()`, `addTask()`, `deleteTask()`, `toggleTaskComplete()`
- Uses secure random bytes for task ID generation

**validation.php**
- Server-side input validation
- Functions: `validateTask()`, `sanitizeTask()`
- Validates title (required, max 255 chars), description (optional, max 1000 chars), and priority

**csrf.php**
- CSRF token generation and validation
- Session-based token storage
- Uses `random_bytes()` for cryptographically secure tokens

**flash.php**
- One-time message display system
- Session-based message storage
- Supports success, error, info, and warning message types

### Frontend Pages

**index.php**
- Displays all tasks sorted by status and priority
- Incomplete tasks appear first
- Within each group, tasks are sorted by priority (High → Medium → Low)

**create.php**
- Task creation form with validation
- Retains form data on validation errors
- CSRF protection

**actions.php**
- Handles POST requests for create, delete, and toggle actions
- Returns proper HTTP status codes (405 for non-POST)
- CSRF validation on all actions

### Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation prevents invalid data
- **XSS Prevention**: All output is properly escaped with `htmlspecialchars()`
- **Secure IDs**: Task IDs use `random_bytes()` instead of predictable sequences
- **Method Validation**: Actions only accept POST requests with proper status codes

### Responsive Design

The application is fully responsive with breakpoints at:
- **Desktop**: 900px+ (full layout)
- **Tablet**: 768px - 900px (adjusted layout)
- **Mobile**: < 768px (stacked layout, centered buttons)
- **Small Mobile**: < 480px (optimized for small screens)

## Screenshots

### Empty State
![Empty State](https://github.com/user-attachments/assets/d4c242d2-bbe1-4984-ae15-e66177d2922e)

### Create Task Form
![Create Form](https://github.com/user-attachments/assets/083ca076-0318-4ad5-b91e-3571a755821d)

### Task List with Items
![Task Created](https://github.com/user-attachments/assets/6f7d577c-2180-4042-9a7b-c1ca29d89af0)

### Validation Error
![Validation Error](https://github.com/user-attachments/assets/a2a67e8c-2ca0-4a8c-8bcf-3f0f4536fa34)

### Mobile View
![Mobile View](https://github.com/user-attachments/assets/2b1adc34-f946-4ff4-a8cc-f3f19e15656b)

## Requirements

- PHP 7.4 or higher
- Write permissions for the `data` directory
- Modern web browser with JavaScript enabled (optional, works without JS)

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

This project was created for educational purposes as part of CIS 435 Project 3.

## Author

EmmalynC27

---

**TaskPad** © 2025 | CIS 435 Project 3