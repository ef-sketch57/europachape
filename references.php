<?php
require_once __DIR__ . '/includes/functions.php';

$page_title       = 'Nos références — Europachape';
$meta_description = "Découvrez quelques-uns des chantiers de chape liquide, traditionnelle et industrielle réalisés par Europachape en région Est.";
$nav_active       = 'references';

$refs = [];
try {
    $stmt = db()->query(
        "SELECT r.id, r.titre, r.slug, r.lieu, r.surface,
                (SELECT chemin_fichier FROM reference_images i
                 WHERE i.reference_id = r.id ORDER BY i.ordre, i.id LIMIT 1) AS image
         FROM `references` r
         WHERE r.is_published = 1
         ORDER BY r.date_projet DESC, r.id DESC"
    );
    $refs = $stmt->fetchAll();
} catch (Throwable $e) { /* base non configurée */ }

require __DIR__ . '/partials/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">Accueil</a> &rsaquo; Références</p>
    <h1>Nos réalisations</h1>
    <p>Un aperçu des chantiers que nous avons menés, du pavillon individuel au grand bâtiment
       industriel.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ($refs): ?>
      <div class="grid grid--3">
        <?php foreach ($refs as $ref): ?>
          <article class="card">
            <a class="card__media" href="reference.php?slug=<?= e(urlencode($ref['slug'])) ?>">
              <img src="<?= e($ref['image'] ?: 'assets/img/hero-2.jpg') ?>" alt="<?= e($ref['titre']) ?>" loading="lazy">
            </a>
            <div class="card__body">
              <?php if ($ref['lieu'] || $ref['surface']): ?>
                <span class="card__meta"><?= e(trim(($ref['lieu'] ?? '') . ($ref['surface'] ? ' · ' . $ref['surface'] : ''), ' ·')) ?></span>
              <?php endif; ?>
              <h3><a href="reference.php?slug=<?= e(urlencode($ref['slug'])) ?>"><?= e($ref['titre']) ?></a></h3>
              <span class="card__foot"><a href="reference.php?slug=<?= e(urlencode($ref['slug'])) ?>">Voir le projet →</a></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="lead" style="text-align:center">Nos références seront bientôt disponibles. En attendant,
         <a href="contact.php">contactez-nous</a> pour discuter de votre projet.</p>
    <?php endif; ?>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <div class="cta-band">
      <h2>Votre chantier sera notre prochaine référence</h2>
      <p>Confiez-nous votre projet de chape : qualité et respect des délais garantis.</p>
      <a class="btn btn--primary" href="contact.php">Demander un devis</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
