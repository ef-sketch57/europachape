<?php
/**
 * En-tête commun des pages publiques.
 * Chaque page définit avant l'inclusion :
 *   $page_title       (string)  — titre de l'onglet / SEO
 *   $meta_description (string)  — meta description
 *   $nav_active       (string)  — clé de menu active (accueil|a-propos|services|references|contact)
 *   $canonical        (string?) — URL canonique (optionnel)
 */

require_once __DIR__ . '/../includes/functions.php';

$nav_active   = $nav_active   ?? '';
$page_title   = $page_title   ?? 'Europachape';
$meta_description = $meta_description ?? company_get('accroche', 'Spécialiste de la chape liquide et traditionnelle.');
$raison       = company_get('raison_sociale', 'Europachape');

$menu = [
    'accueil'    => ['Accueil',     'index.php'],
    'a-propos'   => ['À propos',    'a-propos.php'],
    'services'   => ['Services',    'services.php'],
    'references' => ['Références',  'references.php'],
    'contact'    => ['Contact',     'contact.php'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($page_title) ?></title>
  <meta name="description" content="<?= e($meta_description) ?>">
  <?php if (!empty($canonical)): ?><link rel="canonical" href="<?= e($canonical) ?>"><?php endif; ?>
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= e($page_title) ?>">
  <meta property="og:description" content="<?= e($meta_description) ?>">
  <meta property="og:locale" content="fr_FR">
  <link rel="icon" type="image/jpeg" href="assets/img/logo.jpg">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<a class="skip-link" href="#main">Aller au contenu</a>

<header class="site-header">
  <nav class="nav container" aria-label="Navigation principale">
    <a class="brand" href="index.php" aria-label="<?= e($raison) ?> — accueil">
      <img src="assets/img/logo.jpg" alt="Logo <?= e($raison) ?>">
      <span class="brand__name"><?= e($raison) ?></span>
    </a>

    <button class="nav__toggle" type="button" aria-expanded="false" aria-controls="nav-links" aria-label="Ouvrir le menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>

    <ul class="nav__links" id="nav-links">
      <?php foreach ($menu as $key => [$label, $url]): ?>
        <li>
          <a href="<?= e($url) ?>"<?= $nav_active === $key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
        </li>
      <?php endforeach; ?>
      <li class="nav__cta"><a class="btn btn--primary" href="contact.php">Demander un devis</a></li>
    </ul>
  </nav>
</header>

<main id="main">
