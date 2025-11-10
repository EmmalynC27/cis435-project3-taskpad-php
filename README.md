# TaskPad PHP - CIS 435 Project 3

A minimal task tracker web application built with PHP that allows users to create, list, filter, complete, and delete tasks. This project demonstrates server-side PHP development with form handling, validation, and JSON data persistence.

## Features

- **Task Management**: Create, view, complete, and delete tasks
- **Task Properties**: Title (required), description (optional), priority (Low/Medium/High), due date (optional)
- **Filtering**: Search by text and filter by priority or completion status
- **Security**: CSRF protection for all non-idempotent operations
- **Validation**: Server-side input validation with user-friendly error messages
- **Persistence**: JSON file storage for tasks
- **Responsive Design**: Mobile-first layout that works on all devices
- **Flash Messages**: User feedback for all actions
- **PRG Pattern**: Post-Redirect-Get pattern to prevent double submissions

## Prerequisites

- PHP 7.4+ or 8.x
- Web server (built-in PHP server is sufficient)

## Installation & Setup

### Option 1: Using Built-in PHP Server (Recommended)

1. **Install PHP** (if not already installed):
   ```powershell
   # Windows with winget
   winget install PHP.PHP.8.3
   
   # Or with Chocolatey
   choco install php
   ```

2. **Verify PHP installation**:
   ```powershell
   php --version
   ```

3. **Clone or download this project** to your local machine

4. **Navigate to the project directory**:
   ```powershell
   cd path\to\cis435-project3-taskpad-php
   ```

5. **Start the PHP development server**:
   ```powershell
   php -S localhost:8080 -t public
   ```

6. **Open your browser** and go to: `http://localhost:8080`

### Option 2: Using Apache/Nginx

1. Configure your web server to serve files from the `public/` directory
2. Ensure PHP is enabled and configured
3. Set the document root to the `public/` folder

## Project Structure

```
taskpad-php/
├── README.md
├── index.php               # Root level file (not used in web)
├── data/
│   └── tasks.json         # JSON file for task persistence
├── docs/                  # Documentation (UML diagrams will go here)
├── public/                # Web-accessible files (document root)
│   ├── index.php         # Task list & filtering (GET)
│   ├── create.php        # Task creation form (GET) + handler (POST)
│   ├── actions.php       # Complete/Delete handlers (POST)
│   └── assets/
│       ├── styles.css    # Responsive CSS styling
│       └── screenshots/  # Application screenshots
├── src/                  # PHP classes and utilities
│   ├── storage.php       # TaskStorage class for JSON persistence
│   ├── validation.php    # TaskValidator class for input validation
│   ├── csrf.php          # CSRFProtection class for security
│   └── flash.php         # FlashMessages class for user feedback
└── tests/                # Testing files
    ├── run_test.php      # Automated test runner
    ├── test_cases.json   # Test case definitions
    ├── results.txt       # Test execution results
    └── TEST_PLAN.md      # Detailed test documentation
```

## Usage

### Creating Tasks

1. Click "Add New Task" on the main page
2. Fill in the required title and optional fields:
   - **Title**: Required, max 255 characters
   - **Description**: Optional, max 1000 characters
   - **Priority**: Required, choose Low/Medium/High
   - **Due Date**: Optional, must be YYYY-MM-DD format and not in the past
3. Click "Create Task" to save

### Viewing and Filtering Tasks

- The main page shows all tasks with statistics
- Use the filter form to search by:
  - **Text**: Searches in title and description (case-insensitive)
  - **Priority**: Filter by Low, Medium, or High priority
  - **Status**: Show All, Pending, or Completed tasks
- Clear filters by clicking "Clear" or navigating to the main page

### Managing Tasks

- **Complete**: Click the "✓ Complete" button on any pending task
- **Delete**: Click the "🗑️ Delete" button and confirm the action
- All actions provide flash message feedback

## Data Model

Tasks are stored as JSON objects with the following structure:

```json
{
  "id": "unique-string-identifier",
  "title": "Task title",
  "description": "Optional description",
  "priority": "Low|Medium|High",
  "due": "YYYY-MM-DD|null",
  "completed": true|false,
  "created_at": "YYYY-MM-DD HH:MM:SS",
  "updated_at": "YYYY-MM-DD HH:MM:SS"
}
```

## Security Features

- **CSRF Protection**: All POST requests require valid CSRF tokens
- **Input Validation**: Server-side validation for all form inputs
- **XSS Prevention**: All user data is properly escaped before output
- **Method Validation**: Actions only accept POST requests
- **Token Verification**: Session-based CSRF token validation

## Testing

### Running Automated Tests

Execute the test suite to verify functionality:

```powershell
php tests/run_test.php
```

The test runner will:
- Execute all test cases defined in `tests/test_cases.json`
- Backup and restore data to ensure test isolation
- Generate a detailed report with pass/fail results
- Test coverage includes validation, CSRF protection, filtering, and CRUD operations

### Test Coverage

The test suite includes 15 test cases covering:
- Task creation (valid and invalid inputs)
- Form validation (missing fields, invalid formats)
- Task listing and filtering
- Task completion and deletion
- CSRF protection
- Error handling

See `tests/TEST_PLAN.md` for detailed test case documentation.

## API Endpoints

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/` | Task list with filtering |
| GET | `/create.php` | Task creation form |
| POST | `/create.php` | Create new task |
| POST | `/actions.php` | Complete/delete tasks |

## Requirements Compliance

This project fulfills all CIS 435 Project 3 requirements:

✅ **Server-side PHP application** processing HTML forms  
✅ **Safe use of PHP superglobals** ($_GET, $_POST, $_SERVER)  
✅ **Input validation/sanitization** with friendly error messages  
✅ **JSON file persistence** (no database required)  
✅ **CSRF protection** for non-idempotent operations  
✅ **PRG pattern** implementation to prevent double-submits  
✅ **Responsive design** with semantic HTML/CSS  
✅ **Black-box testing** with automated test runner  
✅ **Proper HTTP responses** including redirects and content types  

## Troubleshooting

### Common Issues

1. **"php is not recognized" error**:
   ```powershell
   # Refresh environment variables
   $env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")
   # Or restart your terminal
   ```

2. **Port already in use**:
   ```powershell
   php -S localhost:8081 -t public
   ```

3. **File permission errors**:
   - Ensure the `data/` directory is writable
   - Check that `data/tasks.json` can be created/modified

4. **Session issues**:
   - Clear browser cookies/session storage
   - Restart the PHP server

### Development Notes

- The application uses PHP sessions for CSRF tokens and flash messages
- JSON data is automatically created on first use
- All file paths are relative to support different hosting environments
- Form data is preserved on validation errors for better UX

## Screenshots

Screenshots demonstrating the application's functionality across different devices are available in `public/assets/screenshots/`.

## License

This project is created for educational purposes as part of CIS 435 coursework at the University of Michigan-Dearborn.

## Setup Instructions

### 1. Installing PHP on Windows

#### Option A: Using Windows Package Manager (Recommended)

If you're on Windows 10/11, you can use the built-in Windows Package Manager:

```powershell
# Search for available PHP versions
winget search php

# Install PHP 8.3 (recommended)
winget install PHP.PHP.8.3
```

After installation, restart your PowerShell/Command Prompt or open a new terminal session to use PHP.

#### Option B: Using Chocolatey

If you have Chocolatey installed:

```powershell
choco install php
```

#### Option C: Manual Installation

1. Download PHP from [php.net/downloads](https://www.php.net/downloads.php)
2. Extract to a folder (e.g., `C:\php`)
3. Add the PHP folder to your system PATH environment variable

### 2. Verify PHP Installation

```powershell
# Check PHP version
php --version

# Verify PHP is working
php -r "echo 'PHP is working!';"
```

**Note**: If you get a "php is not recognized" error after installation, you may need to refresh your environment variables:

```powershell
# Refresh PATH environment variables in current session
$env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")

# Then test PHP again
php --version
```

Alternatively, close and reopen your PowerShell/Command Prompt window.

### 3. Running the Application

Navigate to the project directory and start the PHP development server:

```powershell
# Navigate to project directory
cd C:\path\to\cis435-project3-taskpad-php

# If you just installed PHP and get "php is not recognized", refresh environment variables first:
$env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")

# Start the PHP development server
php -S localhost:8080 -t public
```

The application will be available at [http://localhost:8080](http://localhost:8080)

**Server Output**: When running successfully, you should see:
```
PHP 8.3.27 Development Server (http://localhost:8080) started
```

To stop the server, press `Ctrl+C` in the terminal.

## Project Structure

```
├── README.md
├── data/
│   └── tasks.json          # Task data storage
├── docs/                   # Documentation
├── public/                 # Web-accessible files
│   ├── index.php          # Main application entry point
│   ├── create.php         # Task creation page
│   ├── actions.php        # Task action handlers
│   └── assets/
│       └── styles.css     # Application styles
├── src/                   # Source code
│   ├── csrf.php          # CSRF protection
│   ├── flash.php         # Flash message handling
│   ├── storage.php       # Data persistence
│   └── validation.php    # Input validation
└── tests/                 # Test files
    ├── run_test.php      # Test runner
    ├── test_cases.json   # Test cases
    ├── results.txt       # Test results
    └── TEST_PLAN.md      # Testing documentation
```

## Features

- Create new tasks with title and description
- View all tasks in a clean interface
- Edit existing tasks
- Delete tasks
- Data persistence using JSON file storage
- CSRF protection for secure form submissions
- Input validation and sanitization
- Flash messages for user feedback

## Troubleshooting

### PHP Command Not Found

If you get a "php is not recognized" error after installation:

1. **First, try refreshing environment variables in your current session**:
   ```powershell
   $env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")
   ```

2. **If that doesn't work, restart your terminal/PowerShell session**

3. **Verify PHP is in your system PATH**:
   ```powershell
   where php
   ```

4. **If PHP still isn't found, check the installation**:
   ```powershell
   winget list PHP.PHP.8.3
   ```

### PATH Environment Variable Issues

After installing PHP with winget, the PATH may not be immediately available in your current session:

- **Temporary fix**: Use the PATH refresh command above
- **Permanent fix**: Close and reopen PowerShell/Command Prompt
- **Alternative**: Start a new terminal session

### Permission Issues

If you encounter file permission issues on Windows:

1. Make sure the `data/` directory is writable
2. Run your terminal as Administrator if needed

### Port Already in Use

If port 8080 is already in use:

```powershell
# Use a different port
php -S localhost:8081 -t public
```