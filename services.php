<?php
require_once __DIR__ . '/includes/functions.php';

$page_title       = 'Nos services — Europachape';
$meta_description = "Chape liquide autonivelante, chape traditionnelle, chape industrielle, micro-chape, ravoirage, isolation thermique et phonique, pose de carrelage : découvrez toutes nos prestations.";
$nav_active       = 'services';

require __DIR__ . '/partials/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">Accueil</a> &rsaquo; Services</p>
    <h1>Nos services</h1>
    <p>De la chape autonivelante à la chape industrielle, en passant par l'isolation et la
       pose de revêtements, nous couvrons l'ensemble de vos besoins de sol.</p>
  </div>
</section>

<!-- Détail des deux chapes principales -->
<section class="section">
  <div class="container split">
    <div class="text-content">
      <span class="eyebrow">Notre spécialité</span>
      <h2>Chape liquide autonivelante</h2>
      <p>La chape liquide est un mortier fluide à base de sulfate de calcium, fibré ou non,
         préparé en centrale de production et livré sur chantier en camion malaxeur. Mise en
         œuvre par pompage, elle est <strong>autonivelante et autolissante</strong>.</p>
      <p>Le résultat : un support parfaitement plan, sans joint, prêt à recevoir tous types de
         revêtements (carrelage, parquet, sol souple). Idéale avec un plancher chauffant grâce
         à son excellente conductivité thermique.</p>
    </div>
    <div class="split__media">
      <img src="assets/img/service-2.jpg" alt="Mise en œuvre d'une chape liquide autonivelante">
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container split split--reverse">
    <div class="split__media">
      <img src="assets/img/service-3.jpg" alt="Réalisation d'une chape traditionnelle">
    </div>
    <div class="text-content">
      <span class="eyebrow">Méthode éprouvée</span>
      <h2>Chape traditionnelle</h2>
      <p>La chape traditionnelle est un mortier de sable fin et de ciment. Polyvalente, elle
         s'adapte à de nombreuses configurations et permet de rattraper les niveaux avant la
         pose du revêtement final.</p>
      <p>Adhérente, désolidarisée ou flottante : nous mettons en œuvre la solution la mieux
         adaptée à la nature de votre support et aux contraintes de votre chantier.</p>
    </div>
  </div>
</section>

<!-- Liste complète des prestations -->
<section class="section">
  <div class="container">
    <div class="section__head center">
      <span class="eyebrow">Toutes nos prestations</span>
      <h2>Une offre complète pour vos sols</h2>
    </div>
    <div class="grid grid--3">
      <article class="service"><h3>Chapes</h3><p>Traditionnelle, fluide, anhydrite, ou légère avec polystyrène.</p></article>
      <article class="service"><h3>Chape liquide autonivelante</h3><p>Pour des surfaces planes et homogènes, sans reprise.</p></article>
      <article class="service"><h3>Micro-chape</h3><p>Faible épaisseur pour les rénovations et les contraintes de niveau.</p></article>
      <article class="service"><h3>Ravoirage</h3><p>De 0 à 5 cm pour l'enrobage des gaines et le rattrapage de niveau.</p></article>
      <article class="service"><h3>Pose de carrelage</h3><p>Sol et mur, pour une finition soignée de vos pièces.</p></article>
      <article class="service"><h3>Démolition</h3><p>Petits chantiers de démolition préparatoires à vos travaux.</p></article>
      <article class="service"><h3>Isolation thermique &amp; phonique</h3><p>Pour améliorer le confort et les performances de vos bâtiments.</p></article>
      <article class="service"><h3>Étanchéité des sols</h3><p>Avant et sur chape, pour protéger durablement vos supports.</p></article>
      <article class="service"><h3>Chape industrielle</h3><p>Haute résistance pour locaux à fort passage et usage intensif.</p></article>
    </div>
  </div>
</section>

<!-- Points forts -->
<section class="section section--tint">
  <div class="container split">
    <div class="text-content">
      <span class="eyebrow">Pourquoi nous choisir</span>
      <h2>Nos points forts</h2>
      <ul class="checklist">
        <li>Une équipe dynamique et réactive</li>
        <li>Un équipement matériel perfectionné</li>
        <li>Un devis gratuit sous 48 heures</li>
        <li>Notre expérience et notre sens de l'organisation</li>
      </ul>
    </div>
    <div class="split__media">
      <img src="assets/img/hero-2.jpg" alt="Équipe Europachape sur un chantier">
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cta-band">
      <h2>Besoin d'un conseil sur la chape adaptée ?</h2>
      <p>Nous vous orientons vers la solution la plus pertinente pour votre chantier.</p>
      <a class="btn btn--primary" href="contact.php">Demander un devis gratuit</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
