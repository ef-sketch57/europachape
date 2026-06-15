<?php
require_once __DIR__ . '/includes/functions.php';

$page_title       = 'À propos — Europachape';
$meta_description = "Europachape : société spécialiste de la chape liquide et traditionnelle, implantée en Allemagne en 2005 puis en France en 2008. Plus de 20 ans d'expérience.";
$nav_active       = 'a-propos';

require __DIR__ . '/partials/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">Accueil</a> &rsaquo; À propos</p>
    <h1>À propos d'Europachape</h1>
    <p>Une entreprise familiale forte de plus de 20 ans d'expérience dans la mise en œuvre
       de chapes, au service de la qualité et du respect des délais.</p>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="text-content">
      <span class="eyebrow">Notre histoire</span>
      <h2>Une expertise reconnue dans la chape</h2>
      <p>Europachape a d'abord été implantée en Allemagne en 2005, puis en France en 2008,
         en s'appuyant sur une expérience de plus de 20 ans dans la chape traditionnelle et
         liquide.</p>
      <p>Aujourd'hui composée de 12 collaborateurs, la société s'adresse à un large public :
         particuliers, architectes, entreprises de maçonnerie et bureaux d'études. Installée
         avec un dépôt de plus de 300 m², notre objectif reste constant : satisfaire notre
         clientèle tout en respectant scrupuleusement les délais.</p>
    </div>
    <div class="split__media">
      <img src="assets/img/hero-3.jpg" alt="Chantier de chape réalisé par Europachape">
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <div class="section__head center">
      <span class="eyebrow">Nos valeurs</span>
      <h2>Ce qui nous distingue</h2>
    </div>
    <div class="grid grid--3">
      <article class="service">
        <div class="service__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 6v6c0 5 3.5 8 8 10 4.5-2 8-5 8-10V6z"/></svg></div>
        <h3>Fiabilité</h3>
        <p>Des délais annoncés et tenus, pour que votre chantier avance sans mauvaise surprise.</p>
      </article>
      <article class="service">
        <div class="service__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div>
        <h3>Réactivité</h3>
        <p>Une équipe dynamique et un devis gratuit sous 48 heures pour répondre vite à vos besoins.</p>
      </article>
      <article class="service">
        <div class="service__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 2 2.4 7.4H22l-6 4.4 2.3 7.2L12 16.6 5.7 21l2.3-7.2-6-4.4h7.6z"/></svg></div>
        <h3>Savoir-faire</h3>
        <p>Plus de 20 ans d'expérience et un équipement perfectionné au service de la qualité.</p>
      </article>
    </div>
  </div>
</section>

<section class="section">
  <div class="container split split--reverse">
    <div class="split__media">
      <img src="assets/img/service-1.jpg" alt="Matériel professionnel d'Europachape">
    </div>
    <div class="text-content">
      <span class="eyebrow">Notre matériel</span>
      <h2>Un équipement à la hauteur de vos chantiers</h2>
      <p>Pour garantir qualité et productivité, notre société travaille avec les meilleurs
         outils de son domaine :</p>
      <ul class="checklist">
        <li>Transmix (camion à chape)</li>
        <li>1 machine à pelle</li>
        <li>2 machines à main</li>
        <li>4 camionnettes</li>
      </ul>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <div class="section__head center">
      <span class="eyebrow">Ils nous ont fait confiance</span>
      <h2>Quelques-uns de nos clients</h2>
    </div>
    <div class="grid grid--2">
      <ul class="checklist">
        <li>Luppy — 2 700 m² (EM Bâtiment)</li>
        <li>Ars-sur-Moselle — 4 000 m² (L.C)</li>
        <li>Garage BMW — 3 800 m² (L.C)</li>
        <li>Basse-Ham — 3 000 m² (Bâti Pro)</li>
      </ul>
      <ul class="checklist">
        <li>Jarny — 2 200 m² (L.C)</li>
        <li>Éilange — 2 000 m²</li>
        <li>Fameck — 3 000 m² (L.C)</li>
        <li>Garage BMW Valenciennes</li>
      </ul>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cta-band">
      <h2>Travaillons ensemble sur votre prochain chantier</h2>
      <p>Contactez notre équipe pour étudier votre projet et obtenir un devis personnalisé.</p>
      <a class="btn btn--primary" href="contact.php">Demander un devis</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
