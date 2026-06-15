<?php
require_once __DIR__ . '/includes/functions.php';
http_response_code(404);
$page_title       = 'Page introuvable — Europachape';
$meta_description = 'La page demandée n\'existe pas.';
$nav_active       = '';
require __DIR__ . '/partials/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1>404 — Page introuvable</h1>
    <p>Désolé, la page que vous cherchez n'existe pas ou a été déplacée.</p>
  </div>
</section>
<section class="section">
  <div class="container" style="text-align:center">
    <a class="btn btn--primary" href="index.php">Retour à l'accueil</a>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
