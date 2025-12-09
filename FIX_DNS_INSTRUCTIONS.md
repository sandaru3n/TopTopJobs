# Fix DNS Error for www.toptopjobs.local

## Problem
You're getting `DNS_PROBE_FINISHED_NXDOMAIN` when trying to access `www.toptopjobs.local`. This means Windows can't resolve the domain name.

## Quick Fix (Recommended)

### Option 1: Run PowerShell Script (Easiest)

1. **Right-click on `add-www-domain.ps1`** in this folder
2. Select **"Run with PowerShell"** (or open PowerShell as Administrator)
3. If you get a security warning, type `Y` and press Enter
4. The script will automatically add the domain and flush DNS

**OR** if that doesn't work:

1. Open **PowerShell as Administrator**:
   - Press `Windows Key`
   - Type "PowerShell"
   - Right-click "Windows PowerShell"
   - Select "Run as Administrator"
   - Click "Yes"

2. Navigate to this folder:
   ```powershell
   cd "C:\xampp\htdocs\TopTopJobs\TopTopJobs"
   ```

3. Run the script:
   ```powershell
   .\add-www-domain.ps1
   ```

### Option 2: Manual Fix

1. **Open Notepad as Administrator:**
   - Press `Windows Key`
   - Type "Notepad"
   - Right-click on "Notepad"
   - Select "Run as administrator"
   - Click "Yes"

2. **Open the hosts file:**
   - In Notepad, go to `File` → `Open`
   - Navigate to: `C:\Windows\System32\drivers\etc\`
   - **Important:** Change the file type filter to **"All Files (*.*)"** (not just .txt files)
   - Select the file named `hosts` (no extension)
   - Click "Open"

3. **Add the domain:**
   - Scroll to the bottom of the file
   - You should see: `127.0.0.1   toptopjobs.local`
   - Add this line below it:
     ```
     127.0.0.1   www.toptopjobs.local
     ```
   - Make sure there's a tab or spaces between `127.0.0.1` and `www.toptopjobs.local`

4. **Save the file:**
   - Press `Ctrl + S` or go to `File` → `Save`
   - Close Notepad

5. **Flush DNS cache:**
   - Open Command Prompt as Administrator
   - Run: `ipconfig /flushdns`

## After Fixing

1. **Restart Apache in XAMPP:**
   - Open XAMPP Control Panel
   - Stop Apache (if running)
   - Start Apache again

2. **Clear browser cache:**
   - Close all browser windows
   - Or run `ipconfig /flushdns` in Command Prompt (as Admin)

3. **Test the URLs:**
   - `http://www.toptopjobs.local` ✅
   - `http://toptopjobs.local` ✅

## What Was Already Fixed

✅ **Apache Virtual Host:** Already configured with `ServerAlias www.toptopjobs.local`
✅ **Routes:** Dashboard routes are set up correctly

## Still Having Issues?

If you still get the error after following these steps:

1. Make sure Apache is running in XAMPP
2. Verify the hosts file entry was saved correctly
3. Try accessing `http://toptopjobs.local` (without www) first
4. Clear your browser's DNS cache completely
5. Restart your computer if needed

