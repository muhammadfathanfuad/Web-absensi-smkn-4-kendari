@echo off
REM Script untuk deploy Laravel ke production (Windows)
REM Usage: deploy.bat

echo 🚀 Starting deployment process...

REM 1. Build assets
echo 📦 Building assets for production...
call npm run build

if %errorlevel% neq 0 (
    echo ❌ Error: Failed to build assets
    exit /b 1
)

echo ✅ Assets built successfully

REM 2. Check if public/build exists
if not exist "public\build" (
    echo ❌ Error: public\build directory not found
    exit /b 1
)

echo ✅ Build directory exists

REM 3. Clear cache
echo 🧹 Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo ✅ Cache cleared

REM 4. Optimize for production
echo ⚡ Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ✅ Application optimized

REM 5. Remove public/hot file (if exists)
if exist "public\hot" (
    echo 🔥 Removing public\hot file (dev server indicator)...
    del /f "public\hot"
    echo ✅ Removed public\hot
) else (
    echo ✅ No public\hot file found (good for production)
)

REM 6. Check storage symlink (Windows doesn't support symlinks easily, so we'll just check)
echo 🔗 Checking storage symlink...
php artisan storage:link

REM 7. Check .env file
if not exist ".env" (
    echo ⚠️  Warning: .env file not found
    echo    Please create .env file with production settings
) else (
    echo ✅ .env file exists
    echo    Please verify APP_ENV=production and APP_DEBUG=false
)

echo.
echo ✅ Deployment preparation complete!
echo.
echo 📋 Next steps:
echo    1. Upload all files to hosting (except node_modules)
echo    2. Make sure public\build folder is uploaded
echo    3. Create .env file on hosting with production settings
echo    4. Run on hosting:
echo       - composer install --no-dev --optimize-autoloader
echo       - php artisan storage:link
echo       - php artisan migrate --force (if needed)
echo       - php artisan config:cache
echo       - php artisan route:cache
echo    5. Set correct permissions on hosting
echo.

pause

