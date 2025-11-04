#!/bin/bash

# Barvy
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}╔═══════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║                                           ║${NC}"
echo -e "${BLUE}║     🕐 KAVI Cron Setup                    ║${NC}"
echo -e "${BLUE}║                                           ║${NC}"
echo -e "${BLUE}╚═══════════════════════════════════════════╝${NC}"
echo ""

# Zjistit cestu k projektu
PROJECT_PATH="${1:-/var/www/new.kavi.cz}"

if [ ! -d "$PROJECT_PATH" ]; then
    echo -e "${RED}❌ Projekt nenalezen: $PROJECT_PATH${NC}"
    echo -e "${YELLOW}💡 Použití: bash setup-cron.sh /cesta/k/projektu${NC}"
    exit 1
fi

echo -e "${YELLOW}📂 Projekt: $PROJECT_PATH${NC}"
echo -e "${YELLOW}👤 Uživatel: $(whoami)${NC}"
echo ""

# Zjistit cestu k PHP
PHP_PATH=$(which php)
echo -e "${BLUE}🔍 PHP: $PHP_PATH${NC}"

# Cron příkaz pro Laravel Scheduler
CRON_CMD="* * * * * cd $PROJECT_PATH && $PHP_PATH artisan schedule:run >> /dev/null 2>&1"

# Zkontrolovat, zda cron už existuje
if crontab -l 2>/dev/null | grep -q "schedule:run"; then
    echo -e "${YELLOW}⚠️  Laravel scheduler už je v cronu nastaven!${NC}"
    echo ""
    echo -e "${BLUE}Současné cron jobs:${NC}"
    crontab -l | grep -v "^#" | grep -v "^$"
    echo ""
    
    read -p "$(echo -e ${YELLOW}Chcete ho přepsat? [y/N]: ${NC})" -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${GREEN}✓ Ponechávám současné nastavení${NC}"
        exit 0
    fi
    
    # Odstranit starý cron
    crontab -l 2>/dev/null | grep -v "schedule:run" | crontab -
    echo -e "${GREEN}✓ Starý cron odstraněn${NC}"
fi

# Přidat nový cron
echo -e "${YELLOW}📝 Přidávám Laravel scheduler do cronu...${NC}"
(crontab -l 2>/dev/null; echo "$CRON_CMD") | crontab -

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Cron úspěšně nastaven!${NC}"
    echo ""
    echo -e "${BLUE}╔═══════════════════════════════════════════╗${NC}"
    echo -e "${BLUE}║  Naplánované úlohy (z app/Console/Kernel.php)  ║${NC}"
    echo -e "${BLUE}╚═══════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "  ${GREEN}✓${NC} 03:00 - Čištění login tokenů"
    echo -e "  ${GREEN}✓${NC} 04:00 - Obnovení pozastavených předplatných"
    echo -e "  ${GREEN}✓${NC} 09:00 - Upozornění na platby"
    echo -e "  ${GREEN}✓${NC} 10:00 - ${YELLOW}Trustpilot review requesty${NC}"
    echo -e "  ${GREEN}✓${NC} 16. den měsíce - Aktualizace stock rezervací"
    echo ""
    echo -e "${BLUE}📋 Současné cron jobs:${NC}"
    crontab -l | grep -v "^#" | grep -v "^$"
    echo ""
    echo -e "${GREEN}🎉 Vše hotovo!${NC}"
    echo ""
    echo -e "${YELLOW}💡 Tipy:${NC}"
    echo -e "   • Zkontrolovat logy: tail -f $PROJECT_PATH/storage/logs/laravel.log"
    echo -e "   • Test review requestů: cd $PROJECT_PATH && php artisan reviews:send --dry-run"
    echo -e "   • Odebrat cron: crontab -e"
    echo ""
else
    echo -e "${RED}❌ Chyba při nastavování cronu!${NC}"
    exit 1
fi

