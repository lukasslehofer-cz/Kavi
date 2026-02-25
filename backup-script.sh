#!/bin/bash

# Backup script pro Kavi aplikaci
# Umístění na serveru: /root/backup-kavi.sh
# Automatické spuštění: crontab -e -> 0 2 * * * /root/backup-kavi.sh

# Konfigurace
PROJECT_NAME="kavi_new"
PROJECT_PATH="/var/www/new.kavi.cz"
BACKUP_DIR="/root/backups/kavi"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=7

# Database credentials (změň na své údaje!)
DB_HOST="127.0.0.1"
DB_NAME="kavi_new"
DB_USER="kavi_user"
DB_PASS="tvoje-silne-heslo"

# Vytvoření backup adresáře
mkdir -p "$BACKUP_DIR"

# Funkce pro logování
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

log "🚀 Starting backup for $PROJECT_NAME..."

# 1. Backup databáze
log "📦 Backing up database..."
if mysqldump -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_DIR/${PROJECT_NAME}_db_${DATE}.sql" 2>/dev/null; then
    log "✅ Database backup successful: ${PROJECT_NAME}_db_${DATE}.sql"
    
    # Komprese databáze
    gzip "$BACKUP_DIR/${PROJECT_NAME}_db_${DATE}.sql"
    log "✅ Database compressed: ${PROJECT_NAME}_db_${DATE}.sql.gz"
else
    log "❌ Database backup failed!"
fi

# 2. Backup storage adresáře (faktury, obrázky)
log "📦 Backing up storage files..."
if tar -czf "$BACKUP_DIR/${PROJECT_NAME}_storage_${DATE}.tar.gz" -C "$PROJECT_PATH" storage/app 2>/dev/null; then
    log "✅ Storage backup successful: ${PROJECT_NAME}_storage_${DATE}.tar.gz"
else
    log "❌ Storage backup failed!"
fi

# 3. Backup nahraných obrázků (produkty, pražírny, blog, promo)
log "📦 Backing up uploaded images..."
if tar -czf "$BACKUP_DIR/${PROJECT_NAME}_images_${DATE}.tar.gz" -C "$PROJECT_PATH" public/images 2>/dev/null; then
    log "✅ Images backup successful: ${PROJECT_NAME}_images_${DATE}.tar.gz"
else
    log "❌ Images backup failed!"
fi

# 4. Backup .env souboru
log "📦 Backing up .env configuration..."
if [ -f "$PROJECT_PATH/.env" ]; then
    cp "$PROJECT_PATH/.env" "$BACKUP_DIR/${PROJECT_NAME}_env_${DATE}.txt"
    log "✅ .env backup successful: ${PROJECT_NAME}_env_${DATE}.txt"
else
    log "⚠️  .env file not found!"
fi

# 5. Smazání starých backupů
log "🧹 Cleaning up old backups (older than $RETENTION_DAYS days)..."
find "$BACKUP_DIR" -name "${PROJECT_NAME}_*" -type f -mtime +$RETENTION_DAYS -delete
DELETED_COUNT=$(find "$BACKUP_DIR" -name "${PROJECT_NAME}_*" -type f -mtime +$RETENTION_DAYS | wc -l)
log "✅ Removed $DELETED_COUNT old backup files"

# 6. Kontrola disk space
log "💾 Disk space check..."
DISK_USAGE=$(df -h "$BACKUP_DIR" | awk 'NR==2 {print $5}' | sed 's/%//')
log "📊 Backup directory disk usage: ${DISK_USAGE}%"

if [ "$DISK_USAGE" -gt 80 ]; then
    log "⚠️  WARNING: Disk usage is above 80%!"
fi

# 7. Velikost backupů
BACKUP_SIZE=$(du -sh "$BACKUP_DIR" | awk '{print $1}')
log "📊 Total backup size: $BACKUP_SIZE"

# 8. Seznam nejnovějších backupů
log "📋 Latest backups:"
ls -lht "$BACKUP_DIR" | head -n 6

log "🎉 Backup completed successfully!"
log "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

exit 0

