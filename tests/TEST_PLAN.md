# TaskPad PHP - Test Plan

## Overview
This document outlines the black-box test cases for the TaskPad PHP application covering all major functionality including task creation, validation, filtering, completion, and deletion.

## Test Environment
- **Application**: TaskPad PHP
- **Test Type**: Black-box testing
- **Test Method**: Automated HTTP request simulation
- **Data Storage**: JSON file (data/tasks.json)

## Test Cases

### TC01: Create Task - Happy Path
- **ID**: TC01
- **Purpose**: Verify successful task creation with all valid inputs
- **Inputs**: 
  - title: "Buy groceries"
  - description: "Milk, bread, and eggs"
  - priority: "Medium"
  - due: "2025-12-01"
- **Pre-state**: Empty or existing tasks
- **Steps**: POST to create.php with valid data and CSRF token
- **Expected Result**: 
  - Status: 302 (redirect)
  - Redirect to: index.php
  - Task saved to JSON file
- **Post-state**: New task added to data/tasks.json

### TC02: Create Task - Missing Title
- **ID**: TC02
- **Purpose**: Verify validation error when title is missing
- **Inputs**: 
  - title: ""
  - description: "Some description"
  - priority: "High"
  - due: ""
- **Pre-state**: Any state
- **Steps**: POST to create.php with empty title
- **Expected Result**: 
  - Status: 200
  - HTML contains: "Title is required"
  - Form re-rendered with user inputs preserved
- **Post-state**: No new task created

### TC03: Create Task - Invalid Date Format
- **ID**: TC03
- **Purpose**: Verify validation error for invalid date format
- **Inputs**: 
  - title: "Test task"
  - description: ""
  - priority: "Low"
  - due: "invalid-date"
- **Pre-state**: Any state
- **Steps**: POST to create.php with invalid date
- **Expected Result**: 
  - Status: 200
  - HTML contains: "Due date must be in YYYY-MM-DD format"
- **Post-state**: No new task created

### TC04: Create Task - Invalid Priority
- **ID**: TC04
- **Purpose**: Verify validation error for invalid priority
- **Inputs**: 
  - title: "Test task"
  - description: ""
  - priority: "Invalid"
  - due: ""
- **Pre-state**: Any state
- **Steps**: POST to create.php with invalid priority
- **Expected Result**: 
  - Status: 200
  - HTML contains: "Priority must be Low, Medium, or High"
- **Post-state**: No new task created

### TC05: Create Task - Past Due Date
- **ID**: TC05
- **Purpose**: Verify validation error for past due date
- **Inputs**: 
  - title: "Test task"
  - description: ""
  - priority: "Medium"
  - due: "2020-01-01"
- **Pre-state**: Any state
- **Steps**: POST to create.php with past date
- **Expected Result**: 
  - Status: 200
  - HTML contains: "Due date cannot be in the past"
- **Post-state**: No new task created

### TC06: List Tasks - No Filters
- **ID**: TC06
- **Purpose**: Verify all tasks are displayed without filters
- **Inputs**: GET request to index.php with no query parameters
- **Pre-state**: At least 3 tasks in system
- **Steps**: GET index.php
- **Expected Result**: 
  - Status: 200
  - HTML contains all tasks
  - Statistics displayed correctly
- **Post-state**: No change

### TC07: Filter Tasks - Text Search
- **ID**: TC07
- **Purpose**: Verify text search filters tasks correctly
- **Inputs**: GET index.php?q=groceries
- **Pre-state**: Tasks with "groceries" in title/description exist
- **Steps**: GET index.php with search query
- **Expected Result**: 
  - Status: 200
  - Only matching tasks displayed
  - Search term preserved in form
- **Post-state**: No change

### TC08: Filter Tasks - Priority Filter
- **ID**: TC08
- **Purpose**: Verify priority filter works correctly
- **Inputs**: GET index.php?priority=High
- **Pre-state**: Tasks with different priorities exist
- **Steps**: GET index.php with priority filter
- **Expected Result**: 
  - Status: 200
  - Only High priority tasks displayed
  - Priority filter preserved in form
- **Post-state**: No change

### TC09: Filter Tasks - No Matches
- **ID**: TC09
- **Purpose**: Verify empty state when no tasks match filters
- **Inputs**: GET index.php?q=nonexistent
- **Pre-state**: No tasks match search term
- **Steps**: GET index.php with non-matching search
- **Expected Result**: 
  - Status: 200
  - HTML contains "No matching tasks found"
  - Link to show all tasks
- **Post-state**: No change

### TC10: Complete Task - Valid ID
- **ID**: TC10
- **Purpose**: Verify task can be marked as completed
- **Inputs**: 
  - action: "complete"
  - task_id: valid existing task ID
  - csrf_token: valid token
- **Pre-state**: Uncompleted task exists
- **Steps**: POST to actions.php with complete action
- **Expected Result**: 
  - Status: 302 (redirect to index.php)
  - Flash message: "Task marked as completed!"
  - Task marked as completed in JSON
- **Post-state**: Task completed flag set to true

### TC11: Complete Task - Invalid ID
- **ID**: TC11
- **Purpose**: Verify error handling for invalid task ID
- **Inputs**: 
  - action: "complete"
  - task_id: "nonexistent"
  - csrf_token: valid token
- **Pre-state**: Task ID does not exist
- **Steps**: POST to actions.php with invalid task ID
- **Expected Result**: 
  - Status: 302 (redirect to index.php)
  - Flash message: "Task not found."
- **Post-state**: No changes to data

### TC12: Delete Task - Valid ID
- **ID**: TC12
- **Purpose**: Verify task can be deleted
- **Inputs**: 
  - action: "delete"
  - task_id: valid existing task ID
  - csrf_token: valid token
- **Pre-state**: Task exists in system
- **Steps**: POST to actions.php with delete action
- **Expected Result**: 
  - Status: 302 (redirect to index.php)
  - Flash message: "Task deleted successfully!"
  - Task removed from JSON
- **Post-state**: Task no longer exists in data/tasks.json

### TC13: CSRF Protection - Invalid Token
- **ID**: TC13
- **Purpose**: Verify CSRF protection blocks invalid tokens
- **Inputs**: 
  - action: "delete"
  - task_id: valid task ID
  - csrf_token: "invalid"
- **Pre-state**: Task exists
- **Steps**: POST to actions.php with invalid CSRF token
- **Expected Result**: 
  - Status: 302 (redirect to index.php)
  - Flash message: "Invalid request. Please try again."
- **Post-state**: No changes to data

### TC14: Actions - Invalid Method
- **ID**: TC14
- **Purpose**: Verify only POST method is allowed for actions
- **Inputs**: GET request to actions.php
- **Pre-state**: Any state
- **Steps**: GET actions.php
- **Expected Result**: 
  - Status: 405 (Method Not Allowed)
  - Redirect to index.php
  - Flash message: "Method not allowed."
- **Post-state**: No changes

### TC15: Filter - Combined Filters
- **ID**: TC15
- **Purpose**: Verify multiple filters work together
- **Inputs**: GET index.php?q=test&priority=High&completed=false
- **Pre-state**: Tasks with various combinations exist
- **Steps**: GET index.php with multiple filters
- **Expected Result**: 
  - Status: 200
  - Only tasks matching ALL criteria displayed
  - All filter values preserved in form
- **Post-state**: No change

## Test Data Requirements

For comprehensive testing, the following test data should be available:

1. **Task with all fields**: Title, description, priority, due date
2. **Task with minimal fields**: Title and priority only
3. **Completed task**: For testing completion status filters
4. **Tasks with different priorities**: Low, Medium, High
5. **Tasks with different due dates**: Past, present, future
6. **Tasks with searchable content**: Various keywords in titles/descriptions

## Test Execution Notes and Success Criteria 

All tests should be run with a clean session (new CSRF tokens) and they should also be run in isolation to avoid dependencies. Relative dates from the current date should be used for any tests dependent on dates. All user input should be displayed properly in output. In addition, HTTP 
status codes should be verified for proper rest behavior. 

In order for tests to be considered successful, all test cases should pass
with expected results with no PHP errors or warnings generated. JSON data
integrity needs to be maintained as well as proper HTTP status codes returned. Security features should work correctly as well as expected experience for the user. 
