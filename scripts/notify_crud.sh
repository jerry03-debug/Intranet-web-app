#!/bin/bash
# =============================================================
# notify_crud.sh — Notification par e-mail des actions CRUD
# Lit la table action_logs et envoie un mail pour chaque
# nouvelle action non encore notifiée.
# À exécuter via cron toutes les minutes.
# =============================================================

MAIL_FROM="admin@smarttech.sn"
MAIL_TO="admin@smarttech.sn"
SMTP_SERVER="mail.smarttech.sn"
LAST_ID_FILE="/var/www/smarttech-webapp/scripts/.last_notified_id"

DB_USER="smarttech_user"
DB_PASS="SmartT3ch_2025!"
DB_NAME="smarttech_db"

# Lire le dernier ID notifié
if [ -f "$LAST_ID_FILE" ]; then
    LAST_ID=$(cat "$LAST_ID_FILE")
else
    LAST_ID=0
fi

# Récupérer les nouvelles actions
QUERY="SELECT id, action, table_name, record_id, details, user_ip, created_at FROM action_logs WHERE id > $LAST_ID ORDER BY id ASC;"

mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -N -e "$QUERY" 2>/dev/null | while IFS=$'\t' read -r id action table_name record_id details user_ip created_at; do

    SUBJECT="[SmartTech CRUD] $action sur $table_name (#$record_id)"
    BODY="Action CRUD détectée sur l'intranet SmartTech.

Action      : $action
Table       : $table_name
ID Enreg.   : $record_id
Détails     : $details
IP Client   : $user_ip
Date/Heure  : $created_at

-- Notification automatique SmartTech --"

    # Envoi via sendmail (fourni par iRedMail/Postfix)
    echo -e "From: $MAIL_FROM\nTo: $MAIL_TO\nSubject: $SUBJECT\nContent-Type: text/plain; charset=UTF-8\n\n$BODY" | sendmail -t

    # Mettre à jour le dernier ID notifié
    echo "$id" > "$LAST_ID_FILE"

done

echo "[$(date)] Notification CRUD exécutée." >> /var/log/smarttech_notify.log
