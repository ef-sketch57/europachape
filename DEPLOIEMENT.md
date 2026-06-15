# Déploiement du site Europachape sur IONOS (hébergement mutualisé)

Ce guide explique comment mettre le site en ligne sur un hébergement mutualisé
IONOS (PHP 8 + Apache + MySQL), **par simple upload FTP**, sans Node.js ni Composer.

---

## 1. Pré-requis côté IONOS

- Un pack d'hébergement mutualisé avec **PHP 8.x** (activé dans l'espace client).
- Une **base de données MySQL** déjà créée. Notez précieusement :
  - l'**hôte** de la base (ce n'est **pas** `localhost` : c'est un nom du type
    `db5012345678.hosting-data.io`),
  - le **nom** de la base (ex. `dbs1234567`),
  - l'**utilisateur** (ex. `dbu1234567`),
  - le **mot de passe**.
- Une adresse e-mail du domaine (ex. `no-reply@europachape.com`) pour l'envoi
  des messages du formulaire de contact.

> 💡 Vérifiez la version de PHP dans l'espace IONOS : **« Sites web & boutiques
> → PHP »**, et choisissez PHP 8.1 ou supérieur. Les extensions **PDO MySQL** et
> **GD** (déjà actives par défaut chez IONOS) sont nécessaires.

---

## 2. Créer les tables et les données (phpMyAdmin)

1. Dans l'espace IONOS, ouvrez **phpMyAdmin** sur votre base.
2. Sélectionnez votre base dans la colonne de gauche.
3. Onglet **Importer** → choisissez le fichier **`sql/install.sql`** → **Exécuter**.
   (Crée les 4 tables : `admin_users`, `company_info`, `references`, `reference_images`.)
4. Onglet **Importer** → choisissez ensuite **`sql/seed.sql`** → **Exécuter**.
   (Crée le compte admin par défaut, les coordonnées et 3 références d'exemple.)

> Vous pouvez aussi copier-coller le contenu des fichiers dans l'onglet **SQL**.

---

## 3. Renseigner la configuration

Le site lit ses identifiants dans `includes/config.php`, **qui n'est pas fourni**
(pour ne jamais publier vos mots de passe).

1. Dupliquez le modèle **`includes/config.sample.php`** en **`includes/config.php`**.
2. Ouvrez `includes/config.php` et remplacez les valeurs :

```php
'db' => [
    'host' => 'db5012345678.hosting-data.io', // l'hôte fourni par IONOS
    'name' => 'dbs1234567',                   // le nom de votre base
    'user' => 'dbu1234567',                   // votre utilisateur
    'pass' => 'VOTRE_MOT_DE_PASSE',
    'charset' => 'utf8mb4',
],
'mail' => [
    'to'   => 'info@europachape.com',         // qui reçoit les messages
    'from' => 'no-reply@europachape.com',     // doit être une adresse du domaine
],
'site' => [
    'base_url' => 'https://www.europachape.com',
    'debug'    => false,                      // laissez false en production
],
```

---

## 4. Envoyer les fichiers par FTP

1. Connectez-vous en FTP/SFTP (FileZilla par ex.) avec les identifiants FTP IONOS.
2. Placez-vous dans le dossier web (souvent la racine, parfois `/` ou un sous-dossier
   selon votre offre).
3. **Envoyez tout le contenu du projet**, en conservant l'arborescence :

```
/ (racine web)
├── index.php  a-propos.php  services.php  references.php  reference.php
├── contact.php  404.php  sitemap.php  robots.txt  .htaccess
├── assets/        (css, js, images du thème)
├── includes/      (config.php À CRÉER, db.php, functions.php, auth.php)
├── partials/      (header / footer publics)
├── admin/         (espace d'administration)
├── uploads/       (photos — voir droits ci-dessous)
└── sql/           (scripts SQL — facultatif sur le serveur)
```

> ⚠️ N'oubliez pas d'envoyer `includes/config.php` (créé à l'étape 3) :
> il est volontairement exclu du dépôt Git mais **doit** être présent sur le serveur.

4. **Droits du dossier `uploads/`** : il doit être accessible en écriture par PHP
   pour recevoir les photos uploadées depuis l'admin. Chez IONOS c'est généralement
   bon par défaut ; sinon réglez les permissions du dossier sur **755**.

---

## 5. Premier accès à l'administration

- Adresse : `https://votre-domaine/admin/`
- **Identifiant par défaut : `admin`**
- **Mot de passe par défaut : `Europachape2026!`**

> 🔐 **IMPORTANT** : connectez-vous, puis allez dans **Tableau de bord →
> « Changer mon mot de passe »** pour définir immédiatement un mot de passe
> personnel. Le mot de passe par défaut ne doit pas rester en place.

Depuis l'admin vous pouvez :
- **Références** : créer / modifier / supprimer des projets, avec upload de
  plusieurs photos (redimensionnées et optimisées automatiquement).
- **Coordonnées** : modifier adresse, téléphone, e-mail, horaires et réseaux
  sociaux — répercutés instantanément sur le site public.

---

## 6. Vérifications après mise en ligne

- [ ] La page d'accueil s'affiche avec le logo et les références d'exemple.
- [ ] La page **Contact** affiche les coordonnées et la carte.
- [ ] Un message de test via le formulaire de contact arrive bien sur l'e-mail `to`.
- [ ] La connexion à `/admin/` fonctionne et le mot de passe a été changé.
- [ ] L'ajout d'une photo dans une référence fonctionne (dossier `uploads` en écriture).
- [ ] `https://votre-domaine/sitemap.php` renvoie bien un XML.

---

## 7. Notes de sécurité

- Les identifiants de base ne sont **jamais** dans le code versionné (`config.php`
  est exclu via `.gitignore`).
- Toutes les requêtes SQL utilisent des **requêtes préparées** (anti-injection).
- L'espace admin est protégé par session sécurisée et **jeton CSRF** sur chaque
  formulaire ; le mot de passe est **haché** (bcrypt) en base.
- Le dossier `uploads/` interdit l'exécution de scripts (`uploads/.htaccess`).
- Si l'envoi d'e-mails est bloqué, vérifiez que l'adresse `from` appartient bien
  à votre domaine IONOS (obligatoire pour passer les filtres anti-spam).
