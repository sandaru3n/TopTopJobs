# Fix Directory Listing on Production Server

## Problem
Your production site (`toptopjobs.com`) is showing a directory listing instead of your website. This happens when:
- Document root is set to `jobportal` folder instead of `public` folder
- Directory browsing is enabled
- `.htaccess` is not properly configured

## Immediate Fix

### Step 1: Upload Updated .htaccess
The root `.htaccess` file has been updated with:
- ✅ `Options -Indexes` - Disables directory browsing
- ✅ Redirects root requests to `public/` folder
- ✅ Blocks access to sensitive directories

**Upload the updated `jobportal/.htaccess` file to your server.**

### Step 2: Verify .htaccess is Working
After uploading, test:
1. Visit `https://toptopjobs.com/` - Should show your website (not directory listing)
2. Try accessing `https://toptopjobs.com/app/` - Should be blocked (403 Forbidden)
3. Try accessing `https://toptopjobs.com/env` - Should be blocked (403 Forbidden)

### Step 3: Check Apache Configuration
If directory listing still appears, check your hosting control panel:

**In cPanel:**
1. Go to "Directory Privacy" or "Indexes"
2. Make sure directory browsing is disabled

**Or contact your hosting provider** to:
- Disable directory browsing
- Set document root to `public` folder (preferred)

## What the Updated .htaccess Does

### 1. Disables Directory Browsing
```apache
Options -Indexes
```
This prevents the directory listing you're seeing.

### 2. Redirects Root to Public
```apache
RewriteCond %{REQUEST_URI} ^/$
RewriteRule ^(.*)$ public/ [L]
```
When someone visits `toptopjobs.com/`, it redirects to `public/` folder.

### 3. Routes All Requests to Public
```apache
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ public/$1 [L]
```
All requests are internally rewritten to the `public/` folder.

### 4. Blocks Sensitive Directories
```apache
RewriteRule ^(app|writable|vendor|tests|database|...) - [F,L]
```
Direct access to sensitive folders is blocked with 403 Forbidden.

## Best Solution (Recommended)

**Change Document Root to `public` folder:**

1. **In cPanel:**
   - Go to "Addon Domains" or "Subdomains"
   - Edit `toptopjobs.com`
   - Change document root from `/public_html/jobportal` to `/public_html/jobportal/public`
   - Save

2. **After changing document root:**
   - Root `.htaccess` will only be used for security
   - Directory listing will be completely disabled
   - Better security overall

## Security Checklist

After fixing, verify:
- [ ] No directory listing visible
- [ ] `https://toptopjobs.com/` shows your website
- [ ] `https://toptopjobs.com/app/` returns 403 Forbidden
- [ ] `https://toptopjobs.com/env` returns 403 Forbidden
- [ ] `https://toptopjobs.com/vendor/` returns 403 Forbidden
- [ ] All routes work correctly (login, post-job, etc.)

## Files to Upload

Make sure these files are on your server:
1. ✅ `jobportal/.htaccess` (updated - disables directory listing)
2. ✅ `jobportal/public/.htaccess` (CodeIgniter routing)
3. ✅ All other project files

## Still Having Issues?

If directory listing still appears after uploading `.htaccess`:

1. **Check file permissions:**
   - `.htaccess` should be readable (644)
   - Make sure Apache can read it

2. **Check Apache configuration:**
   - `AllowOverride All` must be enabled
   - `mod_rewrite` must be enabled

3. **Contact hosting support:**
   - Ask them to disable directory browsing
   - Ask them to set document root to `public` folder

## Quick Test

After uploading the updated `.htaccess`:
- Visit: `https://toptopjobs.com/`
- Should see: Your website homepage
- Should NOT see: Directory listing

The updated `.htaccess` file will fix the directory listing issue!

