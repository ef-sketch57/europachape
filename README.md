# Europachape — Site vitrine

Site web de l'entreprise **Europachape**, spécialiste de la chape liquide et
traditionnelle. Site vitrine moderne et responsive avec espace d'administration,
conçu pour un hébergement mutualisé **IONOS** (PHP 8 + Apache + MySQL), déployable
par simple **upload FTP** (sans Node.js ni Composer).

## Fonctionnalités

**Site public (5 pages)**
- Accueil, À propos, Services, Références (grille + page détail par projet), Contact.
- Design moderne, 100 % responsive, accessible, SEO de base (title/meta, sitemap, robots).
- Formulaire de contact (validation, protection CSRF, anti-spam honeypot, envoi par mail).
- Références et coordonnées chargées depuis la base de données.

**Espace admin** (`/admin/`)
- Connexion sécurisée (mot de passe haché bcrypt, session durcie, CSRF).
- CRUD complet des références avec upload multiple d'images (redimensionnement + optimisation GD).
- Édition des coordonnées de l'entreprise, répercutée immédiatement sur le site.

## Stack technique

- **PHP 8** (PDO MySQL, GD) — aucun framework, aucune dépendance Composer.
- **MySQL / MariaDB** (4 tables, requêtes préparées).
- HTML5 sémantique + CSS moderne (design system par variables) + JavaScript vanilla.

## Structure

```
includes/   config, connexion PDO, fonctions, authentification
partials/   en-tête et pied de page publics
admin/      espace d'administration (login, dashboard, références, coordonnées)
assets/     css, js, images du thème
uploads/    photos des références (écriture requise)
sql/        install.sql (schéma) + seed.sql (données initiales)
```

## Installation & déploiement

Voir le guide détaillé : **[DEPLOIEMENT.md](DEPLOIEMENT.md)**.

En résumé :
1. Importer `sql/install.sql` puis `sql/seed.sql` dans phpMyAdmin (IONOS).
2. Copier `includes/config.sample.php` en `includes/config.php` et y mettre les
   identifiants de la base IONOS.
3. Envoyer tous les fichiers par FTP (dossier `uploads/` en écriture).
4. Se connecter à `/admin/` (`admin` / `Europachape2026!`) **et changer le mot de passe**.

> Le code de l'ancien site (2010) est conservé pour archive dans `_ancien-site/`.
