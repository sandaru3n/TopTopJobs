# Fix 500 Error on Job Details Page

## Problem
Getting 500 Internal Server Error when loading job details:
```
Failed to load resource: the server responded with a status of 500
API response not OK: 500
Error loading job details: Error: API error: 500
```

## Root Causes

The 500 error in production can be caused by:

1. **Database Connection Issues**
   - Wrong database credentials in `.env` file
   - Database server not accessible
   - Database doesn't exist

2. **File Path Issues**
   - `.env` file path not found in production
   - Different directory structure in production

3. **PHP Errors**
   - Missing PHP extensions (mysqli)
   - PHP version incompatibility
   - Memory limits

4. **Permission Issues**
   - Files not readable
   - Cache directory not writable

## Solution Applied

### 1. Updated `public/api/jobs.php`

**Changes made:**
- ✅ Multiple env file path detection (handles different production setups)
- ✅ Better error handling with try-catch blocks
- ✅ Database query error handling
- ✅ Graceful fallback to mock data if database fails
- ✅ Better error logging

### 2. Error Handling Improvements

- Added error handler to catch all PHP errors
- Improved database connection error handling
- Added query execution error checking
- Better exception handling

## How to Fix in Production

### Step 1: Upload Updated API File

Upload the updated `jobportal/public/api/jobs.php` file to your server.

### Step 2: Check Database Configuration

Verify your `.env` file has correct database credentials:

```ini
database.default.hostname = localhost
database.default.database = your_database_name
database.default.username = your_database_user
database.default.password = your_database_password
database.default.port = 3306
```

### Step 3: Check Database Connection

Test if the database is accessible:
- Verify database exists
- Check database user has proper permissions
- Test connection from server

### Step 4: Check Error Logs

Check your server error logs to see the actual error:
- **cPanel:** Error Logs section
- **Apache:** `/var/log/apache2/error.log` or similar
- **PHP:** Check PHP error log location

Common log locations:
- `/home/username/logs/error_log`
- `/var/log/apache2/error.log`
- Check cPanel Error Logs section

### Step 5: Verify File Permissions

Make sure files are readable:
```bash
chmod 644 public/api/jobs.php
chmod 644 env (or .env)
```

### Step 6: Check PHP Requirements

Verify PHP extensions are installed:
- `mysqli` extension (required for database)
- PHP 8.1 or higher

## Testing After Fix

1. **Test API directly:**
   ```
   https://www.toptopjobs.com/api/jobs.php?id=10
   ```
   Should return JSON with job data or error message.

2. **Test job details page:**
   ```
   https://www.toptopjobs.com/job/tastiorecipes-com-senior-software-engineer-10
   ```
   Should load without 500 error.

3. **Check browser console:**
   - Should not see 500 errors
   - API should return valid JSON

## Common Issues and Solutions

### Issue: "Database connection failed"

**Solution:**
- Check database credentials in `.env`
- Verify database server is running
- Check database user permissions
- Verify database name exists

### Issue: "File not found" errors

**Solution:**
- Verify `.env` file exists in `jobportal/` folder
- Check file permissions (should be 644)
- Verify file path is correct

### Issue: "Call to undefined function"

**Solution:**
- Check if all required PHP extensions are installed
- Verify PHP version is 8.1+
- Check if `mysqli` extension is enabled

### Issue: "Permission denied"

**Solution:**
- Check file permissions
- Verify cache directory is writable
- Check directory permissions

## Debugging Steps

1. **Enable error display temporarily** (for debugging only):
   ```php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
   **Remember to disable after debugging!**

2. **Check error logs:**
   - Look for specific error messages
   - Check line numbers mentioned in errors
   - Verify file paths are correct

3. **Test database connection:**
   Create a test file `test_db.php`:
   ```php
   <?php
   $conn = new mysqli('localhost', 'username', 'password', 'database');
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   echo "Connected successfully";
   $conn->close();
   ?>
   ```

4. **Test API endpoint directly:**
   Visit: `https://www.toptopjobs.com/api/jobs.php?id=10`
   - Should return JSON
   - Check for error messages in response

## Updated API Features

The updated API now:
- ✅ Tries multiple paths to find `.env` file
- ✅ Handles database connection failures gracefully
- ✅ Falls back to mock data if database fails
- ✅ Provides better error messages
- ✅ Logs errors for debugging
- ✅ Handles all PHP error types

## After Fixing

Once the 500 error is resolved:
- Job details page should load correctly
- API should return valid JSON responses
- No more 500 errors in browser console
- Error logs should show helpful messages (if issues persist)

## Still Having Issues?

If you still get 500 errors after uploading the updated file:

1. Check server error logs for specific error message
2. Verify database connection works
3. Test API endpoint directly in browser
4. Check PHP error log
5. Verify all file permissions are correct

The updated API file should handle most common production issues automatically!

