#!/bin/bash

# Script untuk deploy Laravel ke production
# Usage: bash deploy.sh

echo "🚀 Starting deployment process..."

# 1. Build assets
echo "📦 Building assets for production..."
npm run build

if [ $? -ne 0 ]; then
    echo "❌ Error: Failed to build assets"
    exit 1
fi

echo "✅ Assets built successfully"

# 2. Check if public/build exists
if [ ! -d "public/build" ]; then
    echo "❌ Error: public/build directory not found"
    exit 1
fi

echo "✅ Build directory exists"

# 3. Clear cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "✅ Cache cleared"

# 4. Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Application optimized"

# 5. Remove public/hot file (if exists)
if [ -f "public/hot" ]; then
    echo "🔥 Removing public/hot file (dev server indicator)..."
    rm public/hot
    echo "✅ Removed public/hot"
else
    echo "✅ No public/hot file found (good for production)"
fi

# 6. Check storage symlink
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
    echo "✅ Storage symlink created"
else
    echo "✅ Storage symlink already exists"
fi

# 7. Check .env file
if [ ! -f ".env" ]; then
    echo "⚠️  Warning: .env file not found"
    echo "   Please create .env file with production settings"
else
    echo "✅ .env file exists"
    
    # Check APP_ENV
    if grep -q "APP_ENV=production" .env; then
        echo "✅ APP_ENV is set to production"
    else
        echo "⚠️  Warning: APP_ENV is not set to production"
    fi
    
    # Check APP_DEBUG
    if grep -q "APP_DEBUG=false" .env; then
        echo "✅ APP_DEBUG is set to false"
    else
        echo "⚠️  Warning: APP_DEBUG is not set to false"
    fi
fi

echo ""
echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Upload all files to hosting (except node_modules)"
echo "   2. Make sure public/build folder is uploaded"
echo "   3. Create .env file on hosting with production settings"
echo "   4. Run on hosting:"
echo "      - composer install --no-dev --optimize-autoloader"
echo "      - php artisan storage:link"
echo "      - php artisan migrate --force (if needed)"
echo "      - php artisan config:cache"
echo "      - php artisan route:cache"
echo "   5. Set correct permissions:"
echo "      - chmod -R 755 storage"
echo "      - chmod -R 755 bootstrap/cache"
echo ""

