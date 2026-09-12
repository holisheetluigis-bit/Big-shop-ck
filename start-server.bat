@echo off
title Ck Shop168 Local Server
echo ====================================================
echo Starting Ck Shop168 PHP & MySQL Web Server...
echo ====================================================
echo.
echo Storefront:   http://localhost:8000/index.php
echo Admin Portal: http://localhost:8000/admin/login.php
echo Username:     maroza
echo Password:     30112007
echo.
echo Opening browser...
start http://localhost:8000/index.php
echo.
echo Server is running. Press Ctrl+C to stop.
echo ====================================================
"C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" -S localhost:8000
pause
