@echo off
echo ========================================
echo Adding www.toptopjobs.local to hosts file
echo ========================================
echo.
echo This script requires Administrator privileges.
echo.

:: Check for admin privileges
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERROR: This script must be run as Administrator!
    echo.
    echo Right-click this file and select "Run as administrator"
    pause
    exit /b 1
)

:: Add the domain to hosts file
echo Adding www.toptopjobs.local to hosts file...
echo 127.0.0.1   www.toptopjobs.local >> C:\Windows\System32\drivers\etc\hosts

if %errorLevel% equ 0 (
    echo.
    echo SUCCESS! Domain added to hosts file.
    echo.
    echo Flushing DNS cache...
    ipconfig /flushdns >nul 2>&1
    echo DNS cache flushed!
    echo.
    echo ========================================
    echo You can now access:
    echo   http://www.toptopjobs.local
    echo   http://toptopjobs.local
    echo ========================================
    echo.
    echo Note: For local development, use HTTP (not HTTPS)
    echo Restart Apache in XAMPP if it's running.
) else (
    echo.
    echo ERROR: Failed to add domain to hosts file.
    echo Please add manually:
    echo   127.0.0.1   www.toptopjobs.local
    echo to C:\Windows\System32\drivers\etc\hosts
)

echo.
pause

