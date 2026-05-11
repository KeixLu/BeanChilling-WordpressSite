@echo off
echo === BeanChilling DB Import ===

:: Find MySQL binary in WAMP (handles any version folder)
set MYSQL_BIN=
for /d %%i in ("C:\wamp64\bin\mysql\mysql*") do set MYSQL_BIN=%%i\bin\mysql.exe

if not defined MYSQL_BIN (
    echo ERROR: MySQL not found in C:\wamp64\bin\mysql\
    echo Make sure WAMP is installed.
    pause
    exit /b 1
)

echo Found MySQL: %MYSQL_BIN%
echo.

:: Create database if it doesn't exist, then import
echo Creating database beanchilling_wp (if not exists)...
"%MYSQL_BIN%" -u root -e "CREATE DATABASE IF NOT EXISTS beanchilling_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo Importing dump...
"%MYSQL_BIN%" -u root beanchilling_wp < "%~dp0beanchilling_wp.sql"

if %ERRORLEVEL% == 0 (
    echo.
    echo Done! Database imported successfully.
) else (
    echo.
    echo ERROR: Import failed. Check that WAMP is running and MySQL is accessible.
)

pause
