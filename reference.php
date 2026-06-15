<?php
require_once __DIR__ . '/includes/functions.php';

$slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
$ref  = null;
$images = [];

if ($slug !== '') {
    try {
        $stmt = db()->prepare("SELECT * FROM `references` WHERE slug = ? AND is_published = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $ref = $stmt->fetch();
        if ($ref) {
            $imgStmt = db()->prepare("SELECT chemin_fichier, alt FROM reference_images WHERE reference_id = ? ORDER BY ordre, id");
            $imgStmt->execute([$ref['id']]);
            $images = $imgStmt->fetchAll();
        }
    } catch (Throwable $e) { /* base non configurée */ }
}

if (!$ref) {
    http_response_code(404);
    $page_title       = 'Projet introuvable — Europachape';
    $meta_description = 'Cette référence n\'existe pas ou n\'est plus disponible.';
    $nav_active       = 'references';
    require __DIR__ . '/partials/header.php';
    ?>
    <section class="page-hero">
      <div class="container">
        <h1>Projet introuvable</h1>
        <p>La référence demandée n'existe pas ou n'est plus disponible.</p>
      </div>
    </section>
    <section class="section">
      <div class="container" style="text-align:center">
        <a class="btn btn--dark" href="references.php">← Retour aux références</a>
      </div>
    </section>
    <?php
    require __DIR__ . '/partials/footer.php';
    exit;
}

$cover            = $images[0]['chemin_fichier'] ?? 'assets/img/hero-2.jpg';
$page_title       = e($ref['titre']) . ' — Références Europachape';
$meta_description = mb_substr(trim((string) $ref['description']), 0, 155);
$nav_active       = 'references';

require __DIR__ . '/partials/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">Accueil</a> &rsaquo; <a href="references.php">Références</a> &rsaquo; <?= e($ref['titre']) ?></p>
    <h1><?= e($ref['titre']) ?></h1>
    <?php
      $metaBits = array_filter([$ref['lieu'], $ref['surface'],
        $ref['date_projet'] ? date('Y', strtotime($ref['date_projet'])) : null]);
    ?>
    <?php if ($metaBits): ?><p><?= e(implode(' · ', $metaBits)) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="text-content">
      <span class="eyebrow">Le projet</span>
      <h2><?= e($ref['titre']) ?></h2>
      <?php foreach (preg_split('/\n\s*\n/', trim((string) $ref['description'])) as $para): ?>
        <?php if (trim($para) !== ''): ?><p><?= nl2br(e($para)) ?></p><?php endif; ?>
      <?php endforeach; ?>
      <p style="margin-top:1.5rem"><a class="btn btn--primary" href="contact.php">Un projet similaire ? Demandez un devis</a></p>
    </div>
    <div class="split__media">
      <img src="<?= e($cover) ?>" alt="<?= e($images[0]['alt'] ?? $ref['titre']) ?>">
    </div>
  </div>
</section>

<?php if (count($images) > 1): ?>
<section class="section section--tint">
  <div class="container">
    <div class="section__head"><span class="eyebrow">Galerie</span><h2>Photos du chantier</h2></div>
    <div class="gallery">
      <?php foreach ($images as $img): ?>
        <a href="<?= e($img['chemin_fichier']) ?>" data-lightbox>
          <img src="<?= e($img['chemin_fichier']) ?>" alt="<?= e($img['alt'] ?: $ref['titre']) ?>" loading="lazy">
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container" style="text-align:center">
    <a class="btn btn--dark" href="references.php">← Toutes nos références</a>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
