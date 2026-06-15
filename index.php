<?php
require_once __DIR__ . '/includes/functions.php';

$page_title       = 'Europachape — Chape liquide & traditionnelle | Metz, Thionville';
$meta_description = "Europachape, spécialiste de la chape liquide et traditionnelle en région Est. Plus de 20 ans d'expérience au service des particuliers, artisans et professionnels.";
$nav_active       = 'accueil';

// Quelques références récentes pour la page d'accueil.
$featured = [];
try {
    $stmt = db()->query(
        "SELECT r.id, r.titre, r.slug, r.lieu, r.surface,
                (SELECT chemin_fichier FROM reference_images i
                 WHERE i.reference_id = r.id ORDER BY i.ordre, i.id LIMIT 1) AS image
         FROM `references` r
         WHERE r.is_published = 1
         ORDER BY r.date_projet DESC, r.id DESC
         LIMIT 3"
    );
    $featured = $stmt->fetchAll();
} catch (Throwable $e) { /* base non configurée : section masquée */ }

require __DIR__ . '/partials/header.php';
?>

<!-- HERO -->
<section class="hero">
  <img class="hero__bg" src="assets/img/hero-1.jpg" alt="" aria-hidden="true">
  <div class="container hero__inner">
    <span class="eyebrow" style="color:var(--accent-light)">Chape liquide &amp; traditionnelle</span>
    <h1>Des sols parfaitement plans, prêts pour tous vos revêtements</h1>
    <p>Europachape met plus de 20 ans d'expérience au service de vos chantiers : chapes
       liquides autonivelantes, chapes traditionnelles, chapes industrielles. Particuliers
       et professionnels, dans toute la région Est.</p>
    <div class="hero__actions">
      <a class="btn btn--primary" href="contact.php">Demander un devis gratuit</a>
      <a class="btn btn--ghost" href="references.php">Voir nos réalisations</a>
    </div>
    <div class="hero__badges">
      <span class="hero__badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Devis gratuit sous 48h</span>
      <span class="hero__badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Délais respectés</span>
      <span class="hero__badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg> Équipement professionnel</span>
    </div>
  </div>
</section>

<!-- SERVICES APERÇU -->
<section class="section">
  <div class="container">
    <div class="section__head center">
      <span class="eyebrow">Nos prestations</span>
      <h2>Une solution de chape pour chaque projet</h2>
      <p class="lead">Du pavillon individuel au bâtiment industriel, nous mettons en œuvre la
         technique la mieux adaptée à votre support et à votre revêtement final.</p>
    </div>
    <div class="grid grid--3">
      <article class="service">
        <div class="service__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg></div>
        <h3>Chape liquide</h3>
        <p>Mortier fluide autonivelant à base de sulfate de calcium, mis en œuvre par pompage
           pour un résultat parfaitement plan et homogène.</p>
      </article>
      <article class="service">
        <div class="service__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
        <h3>Chape traditionnelle</h3>
        <p>Mortier de sable fin et de ciment, idéal pour les supports nécessitant une chape
           rapportée ou désolidarisée.</p>
      </article>
      <article class="service">
        <div class="service__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 20h20M4 20V8l8-5 8 5v12M9 20v-6h6v6"/></svg></div>
        <h3>Chape industrielle</h3>
        <p>Solutions haute résistance pour entrepôts, garages et locaux à fort passage,
           durables et faciles d'entretien.</p>
      </article>
    </div>
    <div style="text-align:center;margin-top:2.5rem">
      <a class="btn btn--dark" href="services.php">Découvrir tous nos services</a>
    </div>
  </div>
</section>

<!-- CHIFFRES -->
<section class="section section--tint">
  <div class="container">
    <div class="stats">
      <div><div class="stat__num">20+</div><div class="stat__label">Ans d'expérience</div></div>
      <div><div class="stat__num">12</div><div class="stat__label">Collaborateurs</div></div>
      <div><div class="stat__num">300 m²</div><div class="stat__label">De dépôt &amp; matériel</div></div>
      <div><div class="stat__num">48h</div><div class="stat__label">Pour votre devis</div></div>
    </div>
  </div>
</section>

<?php if ($featured): ?>
<!-- RÉFÉRENCES -->
<section class="section">
  <div class="container">
    <div class="section__head center">
      <span class="eyebrow">Nos réalisations</span>
      <h2>Quelques-uns de nos chantiers</h2>
    </div>
    <div class="grid grid--3">
      <?php foreach ($featured as $ref): ?>
        <article class="card">
          <a class="card__media" href="reference.php?slug=<?= e(urlencode($ref['slug'])) ?>">
            <img src="<?= e($ref['image'] ?: 'assets/img/hero-2.jpg') ?>" alt="<?= e($ref['titre']) ?>" loading="lazy">
          </a>
          <div class="card__body">
            <?php if ($ref['lieu'] || $ref['surface']): ?>
              <span class="card__meta"><?= e(trim($ref['lieu'] . ($ref['surface'] ? ' · ' . $ref['surface'] : ''), ' ·')) ?></span>
            <?php endif; ?>
            <h3><a href="reference.php?slug=<?= e(urlencode($ref['slug'])) ?>"><?= e($ref['titre']) ?></a></h3>
            <span class="card__foot"><a href="reference.php?slug=<?= e(urlencode($ref['slug'])) ?>">Voir le projet →</a></span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem">
      <a class="btn btn--dark" href="references.php">Toutes nos références</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- APPEL À L'ACTION -->
<section class="section">
  <div class="container">
    <div class="cta-band">
      <h2>Un projet de chape ? Parlons-en.</h2>
      <p>Décrivez-nous votre chantier : nous vous conseillons sur la solution la plus adaptée
         et vous adressons un devis gratuit sous 48 heures.</p>
      <a class="btn btn--primary" href="contact.php">Contactez-nous</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
