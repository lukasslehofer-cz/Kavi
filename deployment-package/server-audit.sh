#!/bin/bash

# Server Audit Script pro Kavi deployment
# Tento script zkontroluje server PŘED tím, než začneš cokoliv instalovat
# Použití: bash server-audit.sh

echo "================================================"
echo "🔍 KAVI SERVER AUDIT - Pre-deployment Check"
echo "================================================"
echo ""

# Barvy pro výstup
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Funkce pro logování
log_info() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warn() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "1. ZÁKLADNÍ INFORMACE O SERVERU"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Hostname
echo "🖥️  Hostname: $(hostname)"
echo "📍 IP adresa: $(hostname -I | awk '{print $1}')"
echo "💻 OS: $(lsb_release -d | cut -f2)"
echo "🕐 Uptime: $(uptime -p)"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "2. DISK SPACE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
df -h | grep -E '^Filesystem|/$|/var|/home'
echo ""

DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ "$DISK_USAGE" -gt 80 ]; then
    log_error "Disk usage je nad 80%! Zvažte uvolnění místa."
elif [ "$DISK_USAGE" -gt 60 ]; then
    log_warn "Disk usage je nad 60%. Sledujte volné místo."
else
    log_info "Disk space je v pořádku ($DISK_USAGE% použito)"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "3. PAMĚŤ (RAM)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
free -h
echo ""

MEMORY_USAGE=$(free | grep Mem | awk '{printf("%.0f", $3/$2 * 100)}')
if [ "$MEMORY_USAGE" -gt 80 ]; then
    log_warn "RAM využití je nad 80%. Může to ovlivnit výkon."
else
    log_info "RAM využití je v pořádku ($MEMORY_USAGE% použito)"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "4. EXISTUJÍCÍ PROJEKTY v /var/www/"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if [ -d "/var/www" ]; then
    log_info "Nalezené projekty:"
    ls -lh /var/www/ | tail -n +2
    echo ""
    
    PROJECT_COUNT=$(ls -1 /var/www/ | wc -l)
    log_warn "Celkem $PROJECT_COUNT projektů/složek v /var/www/"
    
    if [ -d "/var/www/new.kavi.cz" ]; then
        log_error "Složka /var/www/new.kavi.cz již existuje!"
        echo "   Možná budeš muset použít jiný název nebo smazat starou verzi."
    else
        log_info "Složka /var/www/new.kavi.cz je volná ✓"
    fi
else
    log_error "/var/www/ adresář neexistuje!"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "5. PHP VERZE A KONFIGURACE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1)
    log_info "Výchozí PHP: $PHP_VERSION"
    
    # Zkontroluj všechny nainstalované PHP verze
    echo ""
    log_info "Nainstalované PHP verze:"
    dpkg -l | grep php | grep -E 'php[0-9]\.[0-9]' | awk '{print $2}' | sort -u
    echo ""
    
    # Zkontroluj běžící PHP-FPM
    log_info "Běžící PHP-FPM služby:"
    systemctl list-units --type=service --state=running | grep php || echo "   Žádné PHP-FPM služby neběží"
    echo ""
    
    # PHP modules
    log_info "Důležité PHP moduly (pro Laravel):"
    php -m | grep -E 'pdo|mysql|mbstring|xml|curl|zip|gd|bcmath|redis' || log_warn "Některé moduly chybí!"
else
    log_error "PHP není nainstalované!"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "6. COMPOSER"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version --no-ansi)
    log_info "$COMPOSER_VERSION"
else
    log_warn "Composer není nainstalovaný - bude potřeba nainstalovat"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "7. NODE.JS A NPM"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    NPM_VERSION=$(npm -v)
    log_info "Node.js: $NODE_VERSION"
    log_info "npm: $NPM_VERSION"
    
    # Zkontroluj verzi - Laravel Vite potřebuje Node 16+
    NODE_MAJOR=$(node -v | cut -d'.' -f1 | sed 's/v//')
    if [ "$NODE_MAJOR" -lt 16 ]; then
        log_warn "Node.js verze je < 16. Doporučeno 18+"
    fi
else
    log_warn "Node.js není nainstalovaný - bude potřeba nainstalovat"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "8. NGINX"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v nginx &> /dev/null; then
    NGINX_VERSION=$(nginx -v 2>&1)
    log_info "$NGINX_VERSION"
    
    # Status
    if systemctl is-active --quiet nginx; then
        log_info "Nginx běží ✓"
    else
        log_error "Nginx NEBĚŽÍ!"
    fi
    
    # Existující konfigurace
    echo ""
    log_info "Aktivní Nginx sites:"
    ls -1 /etc/nginx/sites-enabled/ 2>/dev/null || log_warn "Žádné aktivní sites"
    echo ""
    
    if [ -f "/etc/nginx/sites-enabled/new.kavi.cz" ] || [ -f "/etc/nginx/sites-available/new.kavi.cz" ]; then
        log_warn "Nginx konfigurace pro new.kavi.cz již existuje!"
    else
        log_info "Nginx konfigurace pro new.kavi.cz je volná ✓"
    fi
else
    log_warn "Nginx není nainstalovaný - bude potřeba nainstalovat"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "9. MYSQL / MARIADB"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v mysql &> /dev/null; then
    MYSQL_VERSION=$(mysql --version)
    log_info "$MYSQL_VERSION"
    
    # Status
    if systemctl is-active --quiet mysql || systemctl is-active --quiet mariadb; then
        log_info "MySQL/MariaDB běží ✓"
        
        # Zkontroluj databáze (pokud máš root heslo, zadej ho)
        echo ""
        log_info "Existující databáze:"
        mysql -u root -p -e "SHOW DATABASES;" 2>/dev/null | tail -n +2 || log_warn "Nepodařilo se připojit k MySQL (zkus zadat heslo manuálně)"
        echo ""
        
        # Zkontroluj, jestli kavi_new už existuje
        DB_EXISTS=$(mysql -u root -p -e "SHOW DATABASES LIKE 'kavi%';" 2>/dev/null | grep -c "kavi")
        if [ "$DB_EXISTS" -gt 0 ]; then
            log_warn "Databáze začínající 'kavi' již existuje(jí)!"
            mysql -u root -p -e "SHOW DATABASES LIKE 'kavi%';" 2>/dev/null
        else
            log_info "Žádná kavi* databáze nenalezena - můžeš vytvořit novou ✓"
        fi
    else
        log_error "MySQL/MariaDB NEBĚŽÍ!"
    fi
else
    log_warn "MySQL není nainstalovaný - bude potřeba nainstalovat"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "10. FIREWALL (UFW)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v ufw &> /dev/null; then
    UFW_STATUS=$(ufw status | head -n 1)
    echo "$UFW_STATUS"
    
    if [[ "$UFW_STATUS" == *"active"* ]]; then
        log_info "UFW je aktivní ✓"
        echo ""
        ufw status numbered | head -n 20
    else
        log_warn "UFW není aktivní - doporučeno zapnout po deploymentu"
    fi
else
    log_warn "UFW není nainstalovaný - doporučeno nainstalovat"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "11. SSL CERTIFIKÁTY (Let's Encrypt)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if command -v certbot &> /dev/null; then
    CERTBOT_VERSION=$(certbot --version)
    log_info "$CERTBOT_VERSION"
    
    # Existující certifikáty
    echo ""
    log_info "Existující certifikáty:"
    certbot certificates 2>/dev/null || log_warn "Žádné certifikáty nebo chyba při čtení"
else
    log_warn "Certbot není nainstalovaný - bude potřeba pro SSL"
fi
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "12. BĚŽÍCÍ PROCESY (TOP CPU/Memory)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
ps aux --sort=-%mem | head -n 11
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "13. CRON JOBS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
log_info "Root cron jobs:"
crontab -l 2>/dev/null || echo "   Žádné cron jobs pro root"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "14. DOPORUČENÍ A VAROVÁNÍ"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Souhrn doporučení
WARNINGS=0

if [ "$DISK_USAGE" -gt 70 ]; then
    log_warn "Disk space je nad 70% - zvažte čištění"
    ((WARNINGS++))
fi

if [ -d "/var/www/new.kavi.cz" ]; then
    log_warn "/var/www/new.kavi.cz už existuje - možný konflikt"
    ((WARNINGS++))
fi

if ! command -v composer &> /dev/null; then
    log_warn "Composer bude potřeba nainstalovat"
    ((WARNINGS++))
fi

if ! command -v node &> /dev/null; then
    log_warn "Node.js bude potřeba nainstalovat"
    ((WARNINGS++))
fi

if ! command -v certbot &> /dev/null; then
    log_warn "Certbot bude potřeba pro SSL certifikáty"
    ((WARNINGS++))
fi

if [ "$WARNINGS" -eq 0 ]; then
    echo ""
    log_info "🎉 Server vypadá dobře pro deployment!"
    echo ""
else
    echo ""
    log_warn "⚠️  Nalezeno $WARNINGS varování - zkontroluj výše"
    echo ""
fi

echo "================================================"
echo "📋 AUDIT DOKONČEN"
echo "================================================"
echo ""
echo "💡 Poznámky:"
echo "   - Ulož si tento výstup pro referenci"
echo "   - Zkontroluj všechna varování před instalací"
echo "   - Zálohuj existující projekty před změnami"
echo "   - Testuj na new.kavi.cz před přepnutím na produkci"
echo ""

exit 0

