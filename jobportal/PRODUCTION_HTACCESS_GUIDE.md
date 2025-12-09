# Production .htaccess Setup Guide

## Understanding .htaccess in Production

When deploying to a real hosting environment, there are **two scenarios** depending on how your hosting provider sets up the document root:

### Scenario 1: Document Root = `public` folder (RECOMMENDED) ✅

**Best Practice:** Set your document root to the `public` folder.

**Structure:**
```
/public/          ← Document root (web server points here)
  /index.php
  /.htaccess
  /api/
  /css/
  /js/
  /uploads/
/app/
/writable/
/vendor/
/.htaccess        ← Root .htaccess (for security only)
```

**What happens:**
- Users access: `https://www.toptopjobs.com/login`
- Web server serves from: `/public/` folder
- Root `.htaccess` is **NOT used** for routing (only for security)
- `public/.htaccess` handles all routing

**Root `.htaccess` purpose:** Only protects sensitive files/directories

---

### Scenario 2: Document Root = `jobportal` folder

**If you CANNOT change document root** (some shared hosting):

**Structure:**
```
/jobportal/       ← Document root (web server points here)
  /.htaccess      ← Root .htaccess (handles routing)
  /public/
    /.htaccess    ← Public .htaccess (handles CI4 routing)
  /app/
  /writable/
```

**What happens:**
- Users access: `https://www.toptopjobs.com/login`
- Root `.htaccess` rewrites to: `/public/login`
- `public/.htaccess` then routes to CodeIgniter

**Root `.htaccess` purpose:** Routes requests to public folder + security

---

## Production .htaccess Configuration

### Root `.htaccess` (jobportal/.htaccess)

**For Scenario 1 (Document Root = public):**
- Only needed for security (blocking sensitive directories)
- Can be minimal or removed if document root is properly set

**For Scenario 2 (Document Root = jobportal):**
- Must handle routing to public folder
- Must protect sensitive files

### Updated Root .htaccess for Production

```apache
# ----------------------------------------------------------------------
# CodeIgniter 4 Root .htaccess - Production Version
# Use this if document root is set to jobportal folder (not public)
# ----------------------------------------------------------------------

<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect /public/ URLs to clean URLs (remove /public/ from URL)
    # This ensures URLs like https://www.toptopjobs.com/public/login 
    # redirect to https://www.toptopjobs.com/login
    RewriteCond %{THE_REQUEST} \s/public/([^\s?]*) [NC]
    RewriteRule ^ /%1 [R=301,L]
    
    # If the request is not for a file or directory that exists
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Rewrite all requests to the public folder
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Deny access to sensitive files and directories
<FilesMatch "^\.">
    Require all denied
</FilesMatch>

# Protect .env file
<Files ".env">
    Require all denied
</Files>

# Protect sensitive directories and files
<IfModule mod_rewrite.c>
    RewriteEngine On
    # Block direct access to app, writable, vendor, tests, database, and other sensitive directories
    RewriteRule ^(app|writable|vendor|tests|database|spark|composer\.(json|lock)|phpunit\.xml\.dist|\.env|\.git) - [F,L]
</IfModule>
```

### Public `.htaccess` (public/.htaccess)

**Already configured correctly** - handles CodeIgniter routing.

**For Production, you may want to add:**

```apache
# Force HTTPS (uncomment for production)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Force www (optional - uncomment if you want to force www)
# RewriteCond %{HTTP_HOST} !^www\. [NC]
# RewriteRule ^(.*)$ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Or force non-www (optional - uncomment if you want to remove www)
# RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
# RewriteRule ^(.*)$ https://%1%{REQUEST_URI} [L,R=301]
```

---

## Step-by-Step Production Deployment

### Step 1: Determine Your Hosting Setup

**Check with your hosting provider:**
- Can you set document root to `public` folder? → Use Scenario 1
- Must use default document root? → Use Scenario 2

### Step 2: Upload Files

Upload all files from `jobportal` folder to your server.

### Step 3: Configure Document Root

**Option A: Set Document Root to `public` (Recommended)**
- In cPanel: Go to "Addon Domains" or "Subdomains"
- Set document root to: `/path/to/jobportal/public`
- Root `.htaccess` will only be used for security

**Option B: Keep Document Root at `jobportal`**
- Document root stays at: `/path/to/jobportal`
- Root `.htaccess` will handle routing to public folder

### Step 4: Update .env File

```ini
CI_ENVIRONMENT = production

app.baseURL = 'https://www.toptopjobs.com/'
app.forceGlobalSecureRequests = true
app.CSPEnabled = true
```

### Step 5: Update App.php Configuration

Update `allowedHostnames` in `app/Config/App.php`:

```php
public array $allowedHostnames = ['www.toptopjobs.com', 'toptopjobs.com'];
```

### Step 6: Update Public .htaccess (Optional)

Uncomment HTTPS redirect if you want to force HTTPS:

```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Step 7: Set File Permissions

```bash
chmod 755 writable/
chmod 755 public/uploads/
chmod 644 .env
```

### Step 8: Test

1. Test homepage: `https://www.toptopjobs.com/`
2. Test routes: `https://www.toptopjobs.com/login`
3. Test API: `https://www.toptopjobs.com/api/jobs.php`
4. Verify no `/public/` in URLs
5. Test security: Try accessing `https://www.toptopjobs.com/app/` (should be blocked)

---

## Common Hosting Providers

### cPanel / Shared Hosting
- Usually allows setting document root to `public`
- Use Scenario 1 (recommended)

### VPS / Dedicated Server
- Full control over Apache configuration
- Set document root to `public` in virtual host
- Use Scenario 1 (recommended)

### Cloud Platforms (AWS, Azure, etc.)
- Usually allows setting document root
- Use Scenario 1 (recommended)

---

## Troubleshooting

### Issue: 404 Errors on All Pages

**Solution:**
- Check if `mod_rewrite` is enabled
- Verify `.htaccess` files are uploaded
- Check Apache `AllowOverride All` is set

### Issue: `/public/` Appears in URLs

**Solution:**
- Root `.htaccess` redirect rule should handle this
- Check if redirect rule is working
- Verify document root configuration

### Issue: Can Access Sensitive Files

**Solution:**
- Verify root `.htaccess` security rules are active
- Check file permissions
- Ensure `.htaccess` is being read by Apache

---

## Security Checklist

- [ ] Root `.htaccess` blocks access to `app/`, `writable/`, `vendor/`
- [ ] `.env` file is protected
- [ ] File permissions are set correctly (755 for directories, 644 for files)
- [ ] HTTPS is enforced (if using SSL)
- [ ] `CI_ENVIRONMENT = production` in `.env`
- [ ] Error reporting is disabled in production

---

## Summary

**Best Practice:** Set document root to `public` folder (Scenario 1)
- Cleaner setup
- Better security
- Root `.htaccess` only for protection

**Fallback:** If you can't change document root, use Scenario 2
- Root `.htaccess` handles routing
- Works on shared hosting
- Slightly more complex

The `.htaccess` files provided will work in both scenarios!

