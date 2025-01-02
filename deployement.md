Voici un guide pour **build et déployer une application Symfony 7** sur un hébergeur comme Hostinger :

---

### 1. **Préparer votre application Symfony en local**
Assurez-vous que votre application Symfony est prête à être déployée :
1. **Installer les dépendances en mode production :**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. **Créer les assets (CSS, JS, images, etc.) :**
   Si vous utilisez Webpack Encore :
   ```bash
   npm install
   npm run build
   ```
3. **Générer le cache pour l'environnement de production :**
   ```bash
   php bin/console cache:clear --env=prod
   php bin/console cache:warmup --env=prod
   ```

---

### 2. **Préparer les fichiers pour le déploiement**
Vous devrez copier les éléments suivants sur votre hébergeur :
- Le dossier `public/` (point d'entrée pour les utilisateurs),
- Les fichiers dans le dossier `vendor/` (gérés par Composer),
- Vos fichiers d'application (`src/`, `templates/`, `config/`, etc.),
- Les assets compilés (générés dans `public/build` par Webpack Encore, si utilisé).

Assurez-vous de ne **pas copier les fichiers inutiles**, comme :
- `.env` (sensible en production, remplacez-le par des variables d'environnement),
- Les fichiers de test (`tests/`),
- Les dépendances de développement (exclues avec `--no-dev`).

---

### 3. **Configurer l'hébergeur**
1. **Créer une base de données :**
   - Connectez-vous à l'administration de Hostinger et créez une base de données MySQL.
   - Notez les informations de connexion (hôte, utilisateur, mot de passe, nom de la base).

2. **Configurer les variables d'environnement :**
   - Créez un fichier `.env.local` dans votre serveur (au même niveau que `.env`) avec le contenu suivant :
     ```env
     APP_ENV=prod
     APP_SECRET=your_app_secret
     DATABASE_URL=mysql://username:password@hostname:port/dbname
     ```

3. **Configurer le serveur web (Apache ou Nginx) :**
   - Pour Apache, assurez-vous que le fichier `.htaccess` dans le dossier `public/` contient :
     ```apache
     <IfModule mod_rewrite.c>
         RewriteEngine On
         RewriteCond %{REQUEST_FILENAME} !-f
         RewriteCond %{REQUEST_FILENAME} !-d
         RewriteRule ^ index.php [QSA,L]
     </IfModule>
     ```

   - Pointez le **domaine ou sous-domaine** vers le dossier `public/`.

---

### 4. **Transférer les fichiers sur Hostinger**
- Utilisez un logiciel FTP comme FileZilla ou l'interface de gestion des fichiers de Hostinger.
- Transférez les fichiers préparés vers le serveur (en évitant les fichiers inutiles comme mentionné).

---

### 5. **Exécuter les commandes sur le serveur**
Si Hostinger propose un accès SSH, connectez-vous pour exécuter ces commandes :
1. **Installer les dépendances (si nécessaire) :**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. **Exécuter les migrations de base de données :**
   ```bash
   php bin/console doctrine:migrations:migrate --no-interaction
   ```
3. **Vider et réchauffer le cache :**
   ```bash
   php bin/console cache:clear --env=prod
   php bin/console cache:warmup --env=prod
   ```

---

### 6. **Vérifications finales**
1. Accédez à votre site depuis un navigateur et vérifiez que tout fonctionne correctement.
2. Si des erreurs surviennent :
   - Vérifiez les fichiers de log dans `var/log/prod.log`.
   - Assurez-vous que les permissions des dossiers `var/` et `public/` sont correctes :
     ```bash
     chmod -R 775 var/ public/
     chown -R www-data:www-data var/ public/
     ```

---

### 7. **Optimisation pour la production**
1. Activez **OPcache** sur le serveur.
2. Configurez un système de cache (Redis ou Memcached) si nécessaire.
3. Mettez en place un CDN pour les assets (optionnel).

---

Avec ces étapes, votre application Symfony 7 sera déployée correctement sur Hostinger. Si vous rencontrez des problèmes spécifiques, je suis là pour vous aider ! 😊