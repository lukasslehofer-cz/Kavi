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

