<?php
/**
 * En-tête de l'espace admin (pages protégées uniquement).
 * Définir $admin_title et $admin_nav (dashboard|references|company) avant inclusion.
 */
require_once __DIR__ . '/../../includes/auth.php';
start_secure_session();
require_admin();

$admin_title = $admin_title ?? 'Administration';
$admin_nav   = $admin_nav   ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title><?= e($admin_title) ?> — Europachape Admin</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<header class="admin-topbar">
  <a class="brand" href="dashboard.php">Europachape · Admin</a>
  <nav>
    <a href="dashboard.php"  class="<?= $admin_nav === 'dashboard'  ? 'active' : '' ?>">Tableau de bord</a>
    <a href="references.php" class="<?= $admin_nav === 'references' ? 'active' : '' ?>">Références</a>
    <a href="company.php"    class="<?= $admin_nav === 'company'    ? 'active' : '' ?>">Coordonnées</a>
    <a href="../index.php" target="_blank" rel="noopener">Voir le site ↗</a>
    <a href="logout.php">Déconnexion</a>
  </nav>
</header>
<div class="admin-wrap">
<?php foreach (flash_all() as $f): ?>
  <div class="alert alert--<?= $f['type'] === 'ok' ? 'ok' : 'err' ?>"><?= e($f['message']) ?></div>
<?php endforeach; ?>
