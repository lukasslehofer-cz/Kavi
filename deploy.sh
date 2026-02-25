#!/bin/bash
set -e

# Barvy
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m'

# Konfigurace
PROJECT_PATH="/var/www/new.kavi.cz"
DO_BACKUP="${1:-no}"  # Záloha pouze pokud spustíte: bash deploy.sh backup

# Banner
echo -e "${PURPLE}"
cat << "EOF"
╔═══════════════════════════════════════════╗
║                                           ║
║           ☕ Kavi Deployment              ║
║                                           ║
╚═══════════════════════════════════════════╝
EOF
echo -e "${NC}"

cd $PROJECT_PATH || exit 1

# 0. PŘÍPRAVA - Vyčistit bootstrap cache
echo -e "${YELLOW}🧹 Příprava prostředí...${NC}"
rm -f bootstrap/cache/services.php 2>/dev/null || true
rm -f bootstrap/cache/packages.php 2>/dev/null || true
echo -e "${GREEN}✓ Bootstrap cache vyčištěn${NC}"

# 1. MAINTENANCE MODE
echo -e "${YELLOW}🔧 Zapínám maintenance mode...${NC}"
php artisan down --render="errors::503" --retry=60 2>/dev/null || true
echo -e "${GREEN}✓ Maintenance mode aktivní${NC}"

# 2. ZÁLOHA (VOLITELNÁ)
if [ "$DO_BACKUP" = "backup" ]; then
    echo -e "${YELLOW}📦 Vytvářím zálohu...${NC}"
    BACKUP_DIR="$PROJECT_PATH/backups"
    mkdir -p $BACKUP_DIR
    DATE=$(date +%Y%m%d_%H%M%S)
    tar -czf "$BACKUP_DIR/kavi_$DATE.tar.gz" \
        --exclude='vendor' \
        --exclude='node_modules' \
        --exclude='storage/logs/*' \
        --exclude='storage/framework/cache/*' \
        --exclude='storage/framework/sessions/*' \
        --exclude='storage/framework/views/*' \
        --exclude='backups' \
        --exclude='public/build' \
        . 2>/dev/null || true
    echo -e "${GREEN}✓ Záloha: kavi_$DATE.tar.gz${NC}"
    
    # Smazat staré zálohy (>7 dní)
    find $BACKUP_DIR -name "kavi_*.tar.gz" -mtime +7 -delete 2>/dev/null || true
else
    echo -e "${YELLOW}⏭  Záloha přeskočena (pro zálohu: bash deploy.sh backup)${NC}"
fi

# 3. GIT PULL
echo -e "${YELLOW}📥 Stahuji změny z GitHubu...${NC}"
if [ -d ".git" ]; then
    # Stash lokální změny (nahrané obrázky)
    git stash --include-untracked 2>/dev/null || true
    
    # Pull změn
    git fetch --prune origin
    git reset --hard origin/main
    
    # Obnovíme nahrané obrázky
    if git stash list | grep -q "stash@{0}"; then
        git checkout stash@{0} -- public/images/products/ 2>/dev/null || true
        git checkout stash@{0} -- public/images/roasteries/ 2>/dev/null || true
        git checkout stash@{0} -- public/images/blog/ 2>/dev/null || true
        git checkout stash@{0} -- public/images/promo-images/ 2>/dev/null || true
        git stash drop 2>/dev/null || true
    fi
    
    echo -e "${GREEN}✓ Změny staženy (nahrané obrázky zachovány)${NC}"
else
    echo -e "${RED}⚠  Git není inicializován (přeskakuji pull)${NC}"
fi

# 4. COMPOSER
echo -e "${YELLOW}📚 Instaluji PHP dependencies...${NC}"
composer install --optimize-autoloader --no-dev --no-interaction 2>&1 | tail -5
echo -e "${GREEN}✓ Composer dependencies nainstalované${NC}"

# 5. NPM BUILD
echo -e "${YELLOW}🎨 Builduji frontend assets...${NC}"
if command -v npm &> /dev/null; then
    # Instalujeme i dev dependencies (potřebné pro build)
    npm install --no-audit --no-fund 2>&1 | tail -3
    echo -e "${BLUE}   → Running npm run build...${NC}"
    npm run build 2>&1 | grep -E "(vite|build|✓|chunks)" | tail -10
    
    # Po buildu smažeme dev dependencies pro úsporu místa
    npm prune --omit=dev 2>/dev/null || true
    
    echo -e "${GREEN}✓ Frontend build dokončen${NC}"
else
    echo -e "${RED}⚠  npm není nainstalován (přeskakuji build)${NC}"
fi

# 6. MIGRACE
echo -e "${YELLOW}🗄️  Spouštím databázové migrace...${NC}"
php artisan migrate --force 2>&1 | tail -5
echo -e "${GREEN}✓ Migrace dokončeny${NC}"

# 6.5 SEEDERS (pouze pokud je tabulka prázdná)
echo -e "${YELLOW}🌱 Kontroluji shipping rates...${NC}"
SHIPPING_COUNT=$(php artisan tinker --execute="echo \App\Models\ShippingRate::count();" 2>/dev/null | tail -1)
if [ "$SHIPPING_COUNT" = "0" ] || [ -z "$SHIPPING_COUNT" ]; then
    echo -e "${YELLOW}   → Naplňuji shipping rates...${NC}"
    php artisan db:seed --class=ShippingRateSeeder --force 2>&1 | tail -3
    echo -e "${GREEN}✓ Shipping rates naplněny${NC}"
else
    echo -e "${GREEN}✓ Shipping rates již existují ($SHIPPING_COUNT zemí)${NC}"
fi

# 6.9 ZÁLOHA BILLING CACHE
echo -e "${YELLOW}💾 Zálohuji billing cache...${NC}"
BILLING_CACHE_FILE="/tmp/kavi_billing_cache_$$.json"
php artisan tinker --execute="file_put_contents('$BILLING_CACHE_FILE', json_encode(['last_run' => \Cache::get('subscription_billing_cron_last_run')?->toIso8601String(), 'last_summary' => \Cache::get('subscription_billing_cron_last_summary')]));" 2>/dev/null || true
echo -e "${GREEN}✓ Billing cache zálohována${NC}"

# 7. CACHE CLEAR
echo -e "${YELLOW}🧹 Čistím cache...${NC}"
php artisan config:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1
php artisan route:clear > /dev/null 2>&1
echo -e "${GREEN}✓ Cache vyčištěna${NC}"

# 8. CACHE REBUILD
echo -e "${YELLOW}⚡ Rebuilduji cache...${NC}"
php artisan config:cache > /dev/null 2>&1
php artisan route:cache > /dev/null 2>&1
php artisan view:cache > /dev/null 2>&1
echo -e "${GREEN}✓ Cache obnovena${NC}"

# 9. OPTIMALIZACE
echo -e "${YELLOW}⚡ Optimalizuji aplikaci...${NC}"
php artisan optimize > /dev/null 2>&1
echo -e "${GREEN}✓ Optimalizace dokončena${NC}"

# 9.5 OBNOVENÍ BILLING CACHE
if [ -f "$BILLING_CACHE_FILE" ]; then
    echo -e "${YELLOW}💾 Obnovuji billing cache...${NC}"
    php artisan tinker --execute="\$d=json_decode(file_get_contents('$BILLING_CACHE_FILE'),true); if(!empty(\$d['last_run'])){\Cache::put('subscription_billing_cron_last_run',\Carbon\Carbon::parse(\$d['last_run']),now()->addDay());} if(!empty(\$d['last_summary'])){\Cache::put('subscription_billing_cron_last_summary',\$d['last_summary'],now()->addDay());}" 2>/dev/null || true
    rm -f "$BILLING_CACHE_FILE" 2>/dev/null || true
    echo -e "${GREEN}✓ Billing cache obnovena${NC}"
fi

# 10. STORAGE LINK
echo -e "${YELLOW}🔗 Kontroluji storage link...${NC}"
php artisan storage:link > /dev/null 2>&1 || true
echo -e "${GREEN}✓ Storage link zkontrolován${NC}"

# 11. OPRÁVNĚNÍ
echo -e "${YELLOW}🔒 Nastavuji oprávnění...${NC}"
chown -R www-data:www-data $PROJECT_PATH 2>/dev/null || true
chmod -R 755 $PROJECT_PATH 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chmod 600 .env 2>/dev/null || true
echo -e "${GREEN}✓ Oprávnění nastavena${NC}"

# 12. ČIŠTĚNÍ STARÝCH ZÁLOH
if [ "$DO_BACKUP" = "backup" ]; then
    echo -e "${YELLOW}🧹 Čistím staré zálohy (>7 dní)...${NC}"
    if [ -n "${BACKUP_DIR}" ] && [ -d "${BACKUP_DIR}" ]; then
        find "${BACKUP_DIR}" -name "kavi_*.tar.gz" -mtime +7 -delete 2>/dev/null || true
        BACKUP_COUNT=$(ls -1 "${BACKUP_DIR}"/kavi_*.tar.gz 2>/dev/null | wc -l)
        echo -e "${GREEN}✓ Zálohy: $BACKUP_COUNT souborů${NC}"
    fi
fi

# 13. MAINTENANCE MODE OFF
echo -e "${YELLOW}✅ Vypínám maintenance mode...${NC}"
php artisan up
echo -e "${GREEN}✓ Web je opět online${NC}"

# SHRNUTÍ
echo ""
echo -e "${GREEN}╔═══════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║                                           ║${NC}"
echo -e "${GREEN}║     ✅  Deployment dokončen úspěšně!      ║${NC}"
echo -e "${GREEN}║                                           ║${NC}"
echo -e "${GREEN}╚═══════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}📊 Statistiky:${NC}"
if [ -d ".git" ]; then
    LAST_COMMIT=$(git log -1 --pretty=format:'%h - %s' 2>/dev/null)
    LAST_AUTHOR=$(git log -1 --pretty=format:'%an (%ar)' 2>/dev/null)
    echo -e "   ${PURPLE}Commit:${NC} $LAST_COMMIT"
    echo -e "   ${PURPLE}Autor:${NC} $LAST_AUTHOR"
fi
echo -e "   ${PURPLE}PHP:${NC} $(php -v 2>/dev/null | head -n 1 | cut -d' ' -f1-2)"
echo -e "   ${PURPLE}Node:${NC} $(node -v 2>/dev/null || echo 'N/A')"
echo -e "   ${PURPLE}Čas:${NC} $(date +'%Y-%m-%d %H:%M:%S')"
echo ""
echo -e "${BLUE}🌐 Aplikace:${NC} ${GREEN}https://new.kavi.cz${NC}"
echo -e "${BLUE}🛠️  Admin:${NC} ${GREEN}https://new.kavi.cz/admin${NC}"
echo -e "${BLUE}📝 Logy:${NC} tail -f $PROJECT_PATH/storage/logs/laravel.log"
echo ""
echo -e "${YELLOW}💡 Tip:${NC} Pro deployment se zálohou použij: ${PURPLE}bash deploy.sh backup${NC}"
echo ""
