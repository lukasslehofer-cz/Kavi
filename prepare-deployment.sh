#!/bin/bash

# Prepare Deployment Package Script
# Tento script připraví všechny soubory pro deployment na server

echo "================================================"
echo "📦 Příprava Kavi deployment balíčku"
echo "================================================"
echo ""

# Barvy
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warn() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Kontrola, že jsme v root adresáři projektu
if [ ! -f "composer.json" ] || [ ! -f "artisan" ]; then
    echo "❌ Chyba: Tento script musí být spuštěn z root adresáře Kavi projektu!"
    echo "   Aktuální adresář: $(pwd)"
    exit 1
fi

log_info "Spouštím z: $(pwd)"
echo ""

# 1. Vytvoření deployment adresáře
DEPLOY_DIR="deployment-package"
if [ -d "$DEPLOY_DIR" ]; then
    log_warn "Adresář $DEPLOY_DIR už existuje, mažu..."
    rm -rf "$DEPLOY_DIR"
fi

mkdir -p "$DEPLOY_DIR"
log_info "Vytvořen adresář: $DEPLOY_DIR"
echo ""

# 2. Kontrola důležitých souborů
echo "🔍 Kontrola deployment souborů..."
MISSING_FILES=0

check_file() {
    if [ -f "$1" ]; then
        log_info "Nalezen: $1"
    else
        log_warn "CHYBÍ: $1"
        ((MISSING_FILES++))
    fi
}

check_file "DEPLOYMENT_README.md"
check_file "DEPLOYMENT_GUIDE.md"
check_file "DEPLOYMENT_QUICK_START.md"
check_file "DEPLOYMENT_CHECKLIST.md"
check_file "server-audit.sh"
check_file "deploy.sh"
check_file "backup-script.sh"
check_file "nginx-new-kavi.conf"
check_file ".env.example"

echo ""

if [ "$MISSING_FILES" -gt 0 ]; then
    log_warn "$MISSING_FILES souborů chybí - deployment může být neúplný"
    echo ""
fi

# 3. Build production assets
echo "🎨 Building production assets..."
if command -v npm &> /dev/null; then
    npm run build
    if [ $? -eq 0 ]; then
        log_info "Assets úspěšně sestaveny"
    else
        log_warn "Build assets selhal - zkontroluj chyby"
    fi
else
    log_warn "npm není nainstalovaný - assets nebyly sestaveny"
fi
echo ""

# 4. Vytvoření tarball s kódem (bez node_modules, vendor, atd.)
echo "📦 Vytvářím tarball s aplikačním kódem..."

# Vytvoříme tarball přímo v deploy adresáři
tar --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.git' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/data/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='database/database.sqlite' \
    --exclude='deployment-package' \
    --exclude='*.tar.gz' \
    --exclude='.env' \
    --exclude='.env.backup' \
    -czf "$DEPLOY_DIR/kavi-application.tar.gz" \
    .

if [ $? -eq 0 ]; then
    SIZE=$(du -h "$DEPLOY_DIR/kavi-application.tar.gz" | cut -f1)
    log_info "Aplikační kód: $DEPLOY_DIR/kavi-application.tar.gz ($SIZE)"
else
    log_warn "Vytvoření tarballu selhalo!"
fi
echo ""

# 5. Kopírování deployment souborů
echo "📋 Kopíruji deployment dokumentaci a skripty..."

copy_if_exists() {
    if [ -f "$1" ]; then
        cp "$1" "$DEPLOY_DIR/"
        log_info "Zkopírován: $1"
    fi
}

copy_if_exists "DEPLOYMENT_README.md"
copy_if_exists "DEPLOYMENT_GUIDE.md"
copy_if_exists "DEPLOYMENT_QUICK_START.md"
copy_if_exists "DEPLOYMENT_CHECKLIST.md"
copy_if_exists "server-audit.sh"
copy_if_exists "deploy.sh"
copy_if_exists "backup-script.sh"
copy_if_exists "nginx-new-kavi.conf"
copy_if_exists ".env.example"

echo ""

# 6. Nastavení oprávnění pro scripty
echo "🔐 Nastavuji oprávnění pro scripty..."
chmod +x "$DEPLOY_DIR/server-audit.sh" 2>/dev/null && log_info "server-audit.sh je spustitelný"
chmod +x "$DEPLOY_DIR/deploy.sh" 2>/dev/null && log_info "deploy.sh je spustitelný"
chmod +x "$DEPLOY_DIR/backup-script.sh" 2>/dev/null && log_info "backup-script.sh je spustitelný"
echo ""

# 7. Vytvoření README pro balíček
cat > "$DEPLOY_DIR/README.txt" << 'EOF'
╔═══════════════════════════════════════════════════════════════╗
║          KAVI DEPLOYMENT PACKAGE - Instrukce                  ║
╚═══════════════════════════════════════════════════════════════╝

📦 Tento balíček obsahuje vše potřebné pro deployment Kavi na server.

🚀 ZAČNI TADY:
1. Přečti: DEPLOYMENT_README.md
2. Následuj: DEPLOYMENT_QUICK_START.md (30 min)
3. Podrobnosti: DEPLOYMENT_GUIDE.md (pokud potřebuješ více info)

📁 OBSAH BALÍČKU:

┌─ Dokumentace ───────────────────────────────────────────────┐
│ DEPLOYMENT_README.md         - Přehled a quick start         │
│ DEPLOYMENT_QUICK_START.md    - Rychlý průvodce (začni tady!) │
│ DEPLOYMENT_GUIDE.md          - Detailní instrukce            │
│ DEPLOYMENT_CHECKLIST.md      - Kontrolní seznam              │
└─────────────────────────────────────────────────────────────┘

┌─ Skripty ───────────────────────────────────────────────────┐
│ server-audit.sh              - Audit serveru (spusť PRVNÍ!)  │
│ deploy.sh                    - Automatizace deploymentu      │
│ backup-script.sh             - Automatické zálohy            │
└─────────────────────────────────────────────────────────────┘

┌─ Konfigurace ───────────────────────────────────────────────┐
│ nginx-new-kavi.conf          - Nginx server block           │
│ .env.example                 - Environment šablona           │
└─────────────────────────────────────────────────────────────┘

┌─ Aplikace ──────────────────────────────────────────────────┐
│ kavi-application.tar.gz      - Kompletní Laravel aplikace   │
└─────────────────────────────────────────────────────────────┘

⚡ RYCHLÝ START (3 kroky):

1️⃣ Nahraj server-audit.sh na server a spusť:
   scp server-audit.sh root@your-server:/tmp/
   ssh root@your-server "bash /tmp/server-audit.sh"

2️⃣ Nahraj kavi-application.tar.gz na server:
   scp kavi-application.tar.gz root@your-server:/var/www/new.kavi.cz/
   ssh root@your-server "cd /var/www/new.kavi.cz && tar -xzf kavi-application.tar.gz"

3️⃣ Následuj DEPLOYMENT_QUICK_START.md od kroku 3 (Databáze)

⚠️ DŮLEŽITÉ:
- Nejprve spusť server-audit.sh!
- Pokud na serveru běží jiné projekty, čti varování v guides
- Vytvoř NOVOU databázi (nepřepisuj existující!)
- Testuj na new.kavi.cz před přepnutím na produkci

📞 PODPORA:
- Laravel Docs: https://laravel.com/docs/10.x
- V případě problémů viz sekce Troubleshooting v guidech

Hodně štěstí! 🚀

EOF

log_info "Vytvořen: $DEPLOY_DIR/README.txt"
echo ""

# 8. Vytvoření kontrolního souboru s checksums
echo "🔐 Vytvářím checksums..."
(cd "$DEPLOY_DIR" && find . -type f -exec sha256sum {} \; > CHECKSUMS.txt)
log_info "Vytvořen: $DEPLOY_DIR/CHECKSUMS.txt"
echo ""

# 9. Souhrn
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 SOUHRN DEPLOYMENT BALÍČKU"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📁 Umístění: $DEPLOY_DIR/"
echo ""
echo "📋 Obsah:"
ls -lh "$DEPLOY_DIR/" | tail -n +2
echo ""

TOTAL_SIZE=$(du -sh "$DEPLOY_DIR" | cut -f1)
FILE_COUNT=$(find "$DEPLOY_DIR" -type f | wc -l)

log_info "Celková velikost: $TOTAL_SIZE"
log_info "Počet souborů: $FILE_COUNT"
echo ""

# 10. Instrukce pro upload
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📤 INSTRUKCE PRO UPLOAD NA SERVER"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "Varianta 1: Upload vše najednou (DOPORUČENO pro první deployment)"
echo "   scp -r $DEPLOY_DIR/ root@your-server:/tmp/kavi-deploy/"
echo "   ssh root@your-server"
echo ""
echo "Varianta 2: Upload jen aplikaci (pokud máš guides lokálně)"
echo "   scp $DEPLOY_DIR/kavi-application.tar.gz root@your-server:/var/www/new.kavi.cz/"
echo ""
echo "Varianta 3: Nejdřív audit (DOPORUČENO!)"
echo "   scp $DEPLOY_DIR/server-audit.sh root@your-server:/tmp/"
echo "   ssh root@your-server 'bash /tmp/server-audit.sh'"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

log_info "✅ Deployment balíček je připraven!"
echo ""
echo "🎯 Další kroky:"
echo "   1. Přečti $DEPLOY_DIR/README.txt"
echo "   2. Nahraj soubory na server"
echo "   3. Následuj DEPLOYMENT_QUICK_START.md"
echo ""
echo "================================================"

exit 0

