# TaskPad PHP - CIS 435 Project 3

A simple task management application built with PHP that allows users to create, view, edit, and delete tasks.

## Prerequisites

Before running this application, you need to have PHP installed on your system.

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