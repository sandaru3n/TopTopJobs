# Fix DNS Error for www.toptopjobs.local

## Problem
You're getting `DNS_PROBE_FINISHED_NXDOMAIN` when trying to access `https://www.toptopjobs.local/`. 

**Two issues:**
1. ❌ `www.toptopjobs.local` is not in your Windows hosts file
2. ⚠️ You're using HTTPS, but XAMPP doesn't have SSL configured for local development

## Quick Fix - Step 1: Add Domain to Hosts File

### Easiest Method: Run the Batch File

1. **Right-click on `add-www-to-hosts.bat`** in this folder
2. Select **"Run as administrator"**
3. The script will automatically add the domain and flush DNS

### Alternative: Manual Method

1. **Open Notepad as Administrator:**
   - Press `Windows Key`
   - Type "Notepad"
   - Right-click "Notepad" → "Run as administrator"

2. **Open hosts file:**
   - File → Open
   - Navigate to: `C:\Windows\System32\drivers\etc\`
   - Change file type to **"All Files (*.*)"**
   - Open file named `hosts` (no extension)

3. **Add this line at the bottom:**
   ```
   127.0.0.1   www.toptopjobs.local
   ```

4. **Save** (Ctrl + S)

5. **Flush DNS:**
   - Open Command Prompt as Administrator
   - Run: `ipconfig /flushdns`

## Step 2: Use HTTP Instead of HTTPS (Recommended for Local)

**For local development, use HTTP (not HTTPS):**

✅ **Use these URLs:**
- `http://www.toptopjobs.local`
- `http://toptopjobs.local`

❌ **Don't use:**
- `https://www.toptopjobs.local` (SSL not configured)

## Step 3: Restart Apache

1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache again

## Testing

After completing the steps above, test these URLs:
- ✅ `http://www.toptopjobs.local` - Should work
- ✅ `http://toptopjobs.local` - Should work
- ❌ `https://www.toptopjobs.local` - Will fail (SSL not configured)

## Optional: Enable HTTPS for Local Development

If you really need HTTPS for local development, you'll need to:

1. **Generate SSL certificate** (self-signed)
2. **Configure Apache** to use SSL on port 443
3. **Add SSL virtual host** configuration

This is more complex. For most local development, HTTP is sufficient.

**Note:** I've updated the `.htaccess` file to allow both `www` and non-`www` versions, so both will work once the hosts file is updated.

## What Was Fixed

✅ **Apache Virtual Host:** Already configured with `ServerAlias www.toptopjobs.local`
✅ **.htaccess:** Updated to allow www version (removed redirect)
✅ **Routes:** Dashboard routes are configured

## Still Having Issues?

1. Make sure Apache is running in XAMPP
2. Verify hosts file entry: `127.0.0.1   www.toptopjobs.local`
3. Use **HTTP** (not HTTPS) for local development
4. Clear browser cache completely
5. Try accessing `http://toptopjobs.local` first (without www)

