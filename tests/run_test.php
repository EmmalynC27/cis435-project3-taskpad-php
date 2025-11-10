<?php
/**
 * TaskPad PHP - Automated Test Runner
 * 
 * This script executes black-box tests against the TaskPad PHP application
 * by simulating HTTP requests and validating responses.
 */

header('Content-Type: text/plain');

class TestRunner {
    private $testCases;
    private $results = [];
    private $dataBackup;
    
    public function __construct() {
        // Load test cases
        $testCasesJson = file_get_contents(__DIR__ . '/test_cases.json');
        $this->testCases = json_decode($testCasesJson, true);
        
        if (!$this->testCases) {
            die("Error: Could not load test cases from test_cases.json\n");
        }
        
        // Backup current data
        $this->backupData();
    }
    
    /**
     * Backup the current tasks data
     */
    private function backupData() {
        $dataFile = __DIR__ . '/../data/tasks.json';
        if (file_exists($dataFile)) {
            $this->dataBackup = file_get_contents($dataFile);
        }
    }
    
    /**
     * Restore the backed up data
     */
    private function restoreData() {
        if ($this->dataBackup !== null) {
            file_put_contents(__DIR__ . '/../data/tasks.json', $this->dataBackup);
        }
    }
    
    /**
     * Reset to test data
     */
    private function resetTestData() {
        $testData = [
            [
                "id" => "task_1",
                "title" => "Welcome to TaskPad",
                "description" => "This is your first sample task. You can edit or delete it!",
                "priority" => "Medium",
                "due" => null,
                "completed" => false,
                "created_at" => "2025-11-10 12:00:00",
                "updated_at" => "2025-11-10 12:00:00"
            ],
            [
                "id" => "task_2",
                "title" => "Learn PHP Basics",
                "description" => "Study PHP syntax, variables, arrays, and functions for web development.",
                "priority" => "High",
                "due" => "2025-11-15",
                "completed" => false,
                "created_at" => "2025-11-10 12:01:00",
                "updated_at" => "2025-11-10 12:01:00"
            ],
            [
                "id" => "task_3",
                "title" => "Build TaskPad Features",
                "description" => "Implement create, read, update, and delete functionality for tasks.",
                "priority" => "High",
                "due" => "2025-11-20",
                "completed" => true,
                "created_at" => "2025-11-10 12:02:00",
                "updated_at" => "2025-11-10 12:02:00"
            ]
        ];
        
        file_put_contents(__DIR__ . '/../data/tasks.json', json_encode($testData, JSON_PRETTY_PRINT));
    }
    
    /**
     * Execute all test cases
     */
    public function runTests() {
        echo "TaskPad PHP - Automated Test Runner\n";
        echo "===================================\n\n";
        
        $totalTests = count($this->testCases);
        $passedTests = 0;
        
        foreach ($this->testCases as $testCase) {
            echo "Running {$testCase['id']}: {$testCase['desc']}\n";
            
            // Reset test data before each test
            $this->resetTestData();
            
            try {
                $result = $this->executeTest($testCase);
                if ($result['passed']) {
                    echo "  ✓ PASSED\n";
                    $passedTests++;
                } else {
                    echo "  ✗ FAILED: {$result['reason']}\n";
                }
                $this->results[] = $result;
            } catch (Exception $e) {
                echo "  ✗ ERROR: {$e->getMessage()}\n";
                $this->results[] = [
                    'id' => $testCase['id'],
                    'passed' => false,
                    'reason' => 'Exception: ' . $e->getMessage()
                ];
            }
            
            echo "\n";
        }
        
        // Restore original data
        $this->restoreData();
        
        // Print summary
        echo "Test Results Summary\n";
        echo "===================\n";
        echo "Total Tests: $totalTests\n";
        echo "Passed: $passedTests\n";
        echo "Failed: " . ($totalTests - $passedTests) . "\n";
        echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 1) . "%\n\n";
        
        // Print detailed results
        echo "Detailed Results\n";
        echo "================\n";
        foreach ($this->results as $result) {
            $status = $result['passed'] ? 'PASS' : 'FAIL';
            echo "{$result['id']}: $status";
            if (!$result['passed']) {
                echo " - {$result['reason']}";
            }
            echo "\n";
        }
    }
    
    /**
     * Execute a single test case
     */
    private function executeTest($testCase) {
        // Start session for each test
        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
        }
        session_start();
        
        // Generate CSRF token for POST requests
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;
        
        // Prepare request data
        $endpoint = $testCase['endpoint'];
        $method = $testCase['method'];
        
        // Set up superglobals
        $_SERVER['REQUEST_METHOD'] = $method;
        $_GET = [];
        $_POST = [];
        
        if ($method === 'GET' && isset($testCase['get'])) {
            $_GET = $testCase['get'];
        } elseif ($method === 'POST' && isset($testCase['post'])) {
            $_POST = $testCase['post'];
            // Add CSRF token if not explicitly set to invalid
            if (!isset($_POST['csrf_token'])) {
                $_POST['csrf_token'] = $csrfToken;
            }
        }
        
        // Capture output
        ob_start();
        $headers = [];
        
        // Mock header function to capture redirects
        $originalHeaders = [];
        if (function_exists('xdebug_get_headers')) {
            $originalHeaders = xdebug_get_headers();
        }
        
        // Include the endpoint
        try {
            // Change to the project root directory and then to public
            $originalDir = getcwd();
            chdir(__DIR__ . '/../public');
            
            // Extract just the filename from the endpoint
            $filename = basename($endpoint);
            include $filename;
            
            // Restore original directory
            chdir($originalDir);
        } catch (Exception $e) {
            ob_end_clean();
            if (isset($originalDir)) {
                chdir($originalDir);
            }
            throw $e;
        }
        
        $output = ob_get_clean();
        
        // Get headers (simplified approach)
        $newHeaders = [];
        if (function_exists('xdebug_get_headers')) {
            $newHeaders = array_diff(xdebug_get_headers(), $originalHeaders);
        }
        
        // Check for redirect in headers or output
        $isRedirect = false;
        $redirectLocation = '';
        
        foreach ($newHeaders as $header) {
            if (stripos($header, 'Location:') === 0) {
                $isRedirect = true;
                $redirectLocation = trim(substr($header, 9));
                break;
            }
        }
        
        // Determine HTTP status
        $httpStatus = 200;
        if ($isRedirect) {
            $httpStatus = 302;
        }
        
        // Validate expectations
        $expectations = $testCase['expect'];
        $passed = true;
        $reason = '';
        
        // Check status code
        if (isset($expectations['status'])) {
            if ($httpStatus !== $expectations['status']) {
                $passed = false;
                $reason = "Expected status {$expectations['status']}, got $httpStatus";
            }
        }
        
        // Check redirect
        if (isset($expectations['redirect_contains']) && $passed) {
            if (!$isRedirect || strpos($redirectLocation, $expectations['redirect_contains']) === false) {
                $passed = false;
                $reason = "Expected redirect containing '{$expectations['redirect_contains']}', got '$redirectLocation'";
            }
        }
        
        // Check HTML content
        if (isset($expectations['html_contains']) && $passed) {
            foreach ($expectations['html_contains'] as $expectedContent) {
                if (strpos($output, $expectedContent) === false) {
                    $passed = false;
                    $reason = "Expected HTML to contain '$expectedContent'";
                    break;
                }
            }
        }
        
        return [
            'id' => $testCase['id'],
            'passed' => $passed,
            'reason' => $reason,
            'status' => $httpStatus,
            'output_length' => strlen($output),
            'redirect' => $redirectLocation
        ];
    }
}

// Run the tests
try {
    $runner = new TestRunner();
    $runner->runTests();
} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
    exit(1);
}