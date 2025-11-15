# TaskPad PHP - CIS 435 Project 3

A minimal task tracker web application built with PHP. Create, filter, complete, and delete tasks with JSON file storage, CSRF protection, and responsive design.

## Quick Start

**Prerequisites:** PHP 7.4+ or 8.x

1. **Install PHP** (Windows):
   ```powershell
   winget install PHP.PHP.8.3
   ```

2. **Run the application**:
   ```powershell
   cd path\to\cis435-project3-taskpad-php
   php -S localhost:8080 -t public
   ```

3. **Open browser**: `http://localhost:8080`

## Features

- Create, view, complete, and delete tasks
- Search and filter by priority or status
- CSRF protection and input validation
- JSON file storage (no database required)
- Responsive mobile-friendly design

## Project Structure

```
cis435-project3-taskpad-php/
├── public/          # Web files (index.php, create.php, actions.php)
├── src/             # PHP classes (storage, validation, csrf, flash)
├── data/            # JSON task storage
└── tests/           # Automated testing suite
```

## Testing

Run the automated test suite:
```powershell
php tests/run_test.php
```

## CIS 435 Requirements

✅ Server-side PHP with form handling  
✅ Input validation and CSRF protection  
✅ JSON file persistence  
✅ Responsive design  
✅ Black-box testing suite  

## Troubleshooting

**PHP not recognized?**
```powershell
$env:PATH = [System.Environment]::GetEnvironmentVariable("PATH","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("PATH","User")
```

**Port in use?** Try: `php -S localhost:8081 -t public`