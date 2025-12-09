# Testing API Endpoints

## Quick Test

After uploading the updated `.htaccess` files, test these URLs directly in your browser:

1. **API Jobs Endpoint:**
   ```
   https://www.toptopjobs.com/api/jobs.php?id=10
   ```
   Should return JSON with job data.

2. **API Categories Endpoint:**
   ```
   https://www.toptopjobs.com/api/categories.php
   ```
   Should return JSON with categories.

3. **Job Details Page:**
   ```
   https://www.toptopjobs.com/job/tastiorecipes-com-senior-software-engineer-10
   ```
   Should load without 404 errors.

## If Still Getting 404

### Option 1: Test with /public/ in URL
Try accessing the API with `/public/` in the URL:
```
https://www.toptopjobs.com/public/api/jobs.php?id=10
```

If this works, the root `.htaccess` rewrite might need adjustment.

### Option 2: Check File Permissions
Make sure the API file is readable:
```bash
chmod 644 public/api/jobs.php
```

### Option 3: Verify File Exists
Check if the file exists at:
```
jobportal/public/api/jobs.php
```

### Option 4: Check Apache Error Logs
Look for rewrite rule errors in your server error logs.

## Expected Response

The API should return JSON like:
```json
{
  "success": true,
  "job": {
    "id": 10,
    "title": "...",
    "company_name": "...",
    ...
  }
}
```

If you get a 404, the file isn't being found or served correctly.

