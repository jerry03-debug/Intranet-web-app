# SmartTech — Webapp CRUD Intranet
## Guide de déploiement rapide (Ubuntu Server)

---

### 1. Prérequis à installer

```bash
sudo apt update
sudo apt install -y nginx php8.1-fpm php8.1-mysql php8.1-mbstring mysql-server
```

> Si `php8.1` n'est pas disponible, utiliser `php-fpm php-mysql php-mbstring`.

---

### 2. Copier les fichiers

```bash
sudo cp -r smarttech-webapp /var/www/smarttech-webapp
sudo chown -R www-data:www-data /var/www/smarttech-webapp
```

---

### 3. Créer la base de données

```bash
sudo mysql < /var/www/smarttech-webapp/sql/schema.sql
```

Cela crée :
- La base `smarttech_db`
- La table `employes` (avec 3 enregistrements de démo)
- La table `action_logs` (logs pour les notifications)
- L'utilisateur MySQL `smarttech_user`

---

### 4. Générer le certificat SSL auto-signé

```bash
sudo mkdir -p /etc/ssl/smarttech
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/ssl/smarttech/smarttech.key \
  -out /etc/ssl/smarttech/smarttech.crt \
  -subj "/C=SN/ST=Dakar/L=Dakar/O=SmartTech/CN=www.smarttech.sn"
```

---

### 5. Configurer Nginx

```bash
sudo cp /var/www/smarttech-webapp/nginx/smarttech-webapp.conf /etc/nginx/sites-available/smarttech-webapp
sudo ln -s /etc/nginx/sites-available/smarttech-webapp /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
```

> **Note** : Adapter la version de PHP-FPM dans le fichier conf si nécessaire (`php8.1-fpm.sock` → `php8.3-fpm.sock` etc.).

---

### 6. Vérifier le DNS

Ton camarade doit ajouter dans la zone DNS `smarttech.sn` :

```
www       IN  A  <IP_DU_SERVEUR>
intranet  IN  A  <IP_DU_SERVEUR>
```

Pour tester en local sans DNS (sur le client) :

```bash
echo "<IP_DU_SERVEUR> www.smarttech.sn intranet.smarttech.sn" | sudo tee -a /etc/hosts
```

---

### 7. Tester l'application

| Test | URL |
|------|-----|
| HTTP → HTTPS redirect | `http://www.smarttech.sn` |
| Page d'accueil HTTPS | `https://www.smarttech.sn` |
| Ajouter un employé | Cliquer « + Ajouter un employé » |
| Modifier un employé | Cliquer « Modifier » sur une ligne |
| Supprimer un employé | Cliquer « Supprimer » sur une ligne |

---

### 8. Notifications e-mail (CRUD → mail)

Le script `scripts/notify_crud.sh` lit la table `action_logs` et envoie un mail via `sendmail` (Postfix/iRedMail) pour chaque action CRUD.

```bash
# Rendre exécutable
sudo chmod +x /var/www/smarttech-webapp/scripts/notify_crud.sh

# Tester manuellement
sudo /var/www/smarttech-webapp/scripts/notify_crud.sh

# Ajouter au cron (exécution chaque minute)
(sudo crontab -l 2>/dev/null; echo "* * * * * /var/www/smarttech-webapp/scripts/notify_crud.sh") | sudo crontab -
```

---

### 9. Pare-feu

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

### Structure du projet

```
smarttech-webapp/
├── includes/
│   ├── db.php            # Connexion MySQL (PDO)
│   └── employes.php      # Fonctions CRUD
├── public/
│   ├── index.php         # Liste des employés
│   ├── form.php          # Ajout / Modification
│   └── delete.php        # Suppression
├── nginx/
│   └── smarttech-webapp.conf  # Config Nginx HTTP+HTTPS
├── scripts/
│   └── notify_crud.sh    # Notification mail automatique
├── sql/
│   └── schema.sql        # Schéma + données de démo
└── GUIDE.md              # Ce fichier
```

---

### Résumé des credentials

| Élément | Valeur |
|---------|--------|
| Base de données | `smarttech_db` |
| User MySQL | `smarttech_user` |
| Password MySQL | `SmartT3ch_2025!` |
| Domaines | `www.smarttech.sn` / `intranet.smarttech.sn` |
| Certificat SSL | `/etc/ssl/smarttech/` |
