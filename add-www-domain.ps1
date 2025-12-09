# Script to add www.toptopjobs.local to Windows hosts file
# MUST BE RUN AS ADMINISTRATOR

$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$domain = "www.toptopjobs.local"
$ip = "127.0.0.1"

# Check if running as administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "`n========================================" -ForegroundColor Red
    Write-Host "ERROR: This script must be run as Administrator!" -ForegroundColor Red
    Write-Host "========================================`n" -ForegroundColor Red
    Write-Host "To fix this:" -ForegroundColor Yellow
    Write-Host "1. Right-click on PowerShell" -ForegroundColor Yellow
    Write-Host "2. Select 'Run as Administrator'" -ForegroundColor Yellow
    Write-Host "3. Navigate to this folder and run: .\add-www-domain.ps1`n" -ForegroundColor Yellow
    Write-Host "OR manually add this line to C:\Windows\System32\drivers\etc\hosts:" -ForegroundColor Cyan
    Write-Host "$ip`t$domain`n" -ForegroundColor White
    exit 1
}

# Check if domain already exists
$hostsContent = Get-Content $hostsPath -ErrorAction Stop
$domainExists = $hostsContent | Select-String -Pattern "^\s*$ip\s+$domain" -Quiet

if ($domainExists) {
    Write-Host "`n✓ Domain $domain already exists in hosts file." -ForegroundColor Green
} else {
    try {
        # Add the domain to hosts file
        Add-Content -Path $hostsPath -Value "$ip`t$domain" -ErrorAction Stop
        Write-Host "`n✓ Successfully added $domain to hosts file!" -ForegroundColor Green
    } catch {
        Write-Host "`n✗ Error adding domain: $_" -ForegroundColor Red
        exit 1
    }
}

# Flush DNS cache
Write-Host "`nFlushing DNS cache..." -ForegroundColor Yellow
ipconfig /flushdns | Out-Null
Write-Host "✓ DNS cache flushed!`n" -ForegroundColor Green

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "SUCCESS! You can now access:" -ForegroundColor Green
Write-Host "  http://www.toptopjobs.local" -ForegroundColor White
Write-Host "  http://toptopjobs.local" -ForegroundColor White
Write-Host "========================================`n" -ForegroundColor Cyan

Write-Host "Note: Restart Apache in XAMPP if it's running.`n" -ForegroundColor Yellow

