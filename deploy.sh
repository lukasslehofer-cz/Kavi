#!/bin/bash

# Deployment script pro Kavi Laravel aplikaci
# Použití: ./deploy.sh

set -e

echo "🚀 Starting Kavi deployment..."

# Změň na adresář projektu
cd /var/www/new.kavi.cz

# Zapnutí maintenance mode
echo "📦 Enabling maintenance mode..."
php artisan down || true

# Git pull (pokud používáš Git)
if [ -d ".git" ]; then
    echo "📥 Pulling latest changes from Git..."
    # Stash lokální změny (pokud existují) a pull
    git stash --include-untracked || true
    git pull origin main
    # Obnovíme jen nahrané obrázky (pokud byly stashnuté)
    if git stash list | grep -q "stash@{0}"; then
        git checkout stash@{0} -- public/images/products/ 2>/dev/null || true
        git checkout stash@{0} -- public/images/roasteries/ 2>/dev/null || true
        git stash drop || true
    fi
fi

# Update Composer závislostí
echo "📚 Installing/updating Composer dependencies..."
composer install --optimize-autoloader --no-dev

# NPM build
echo "🎨 Building frontend assets..."
npm install --production
npm run build

# Migrace databáze
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear & recache
echo "🧹 Clearing and recaching..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimalizace
echo "⚡ Optimizing application..."
php artisan optimize

# Nastavení oprávnění
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/new.kavi.cz
chmod -R 755 /var/www/new.kavi.cz
chmod -R 775 /var/www/new.kavi.cz/storage
chmod -R 775 /var/www/new.kavi.cz/bootstrap/cache

# Vypnutí maintenance mode
echo "✅ Disabling maintenance mode..."
php artisan up

echo ""
echo "🎉 Deployment complete!"
echo "🌐 Site: https://new.kavi.cz"
echo ""
echo "Don't forget to:"
echo "  - Check logs: tail -f storage/logs/laravel.log"
echo "  - Test the application thoroughly"
echo "  - Monitor server resources"
echo ""

