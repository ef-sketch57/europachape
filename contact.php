<?php
require_once __DIR__ . '/includes/auth.php'; // fournit start_secure_session() + utilitaires
start_secure_session();

$page_title       = 'Contact &amp; devis — Europachape';
$meta_description = "Contactez Europachape pour un devis gratuit : adresse, téléphone, e-mail et formulaire. Réponse assurée sous 48 heures.";
$nav_active       = 'contact';

$errors = [];
$sent   = false;
$old    = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    // Anti-spam : champ honeypot invisible (doit rester vide).
    if (!empty($_POST['website'])) {
        $sent = true; // on fait croire au robot que c'est envoyé
    } else {
        foreach ($old as $k => $_) {
            $old[$k] = trim((string) ($_POST[$k] ?? ''));
        }

        if ($old['name'] === '')                    { $errors['name'] = 'Merci d\'indiquer votre nom.'; }
        if ($old['email'] === '')                   { $errors['email'] = 'Merci d\'indiquer votre e-mail.'; }
        elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Adresse e-mail invalide.'; }
        if (mb_strlen($old['message']) < 15)        { $errors['message'] = 'Votre message doit contenir au moins 15 caractères.'; }

        if (!$errors) {
            global $config;
            $to      = $config['mail']['to']   ?? company_get('email', 'info@europachape.com');
            $from    = $config['mail']['from'] ?? 'no-reply@europachape.com';
            $subject = ($config['mail']['subject'] ?? 'Nouveau message du site') .
                       ($old['subject'] ? ' — ' . $old['subject'] : '');

            $body = "Nouveau message envoyé depuis le formulaire de contact du site.\n\n"
                  . "Nom     : {$old['name']}\n"
                  . "E-mail  : {$old['email']}\n"
                  . "Tél.    : " . ($old['phone'] ?: '—') . "\n"
                  . "Sujet   : " . ($old['subject'] ?: '—') . "\n\n"
                  . "Message :\n{$old['message']}\n";

            // En-têtes : expéditeur du domaine + Reply-To vers le visiteur.
            $headers  = 'From: ' . $from . "\r\n";
            $headers .= 'Reply-To: ' . $old['email'] . "\r\n";
            $headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";

            if (@mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers)) {
                $sent = true;
                $old  = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];
            } else {
                $errors['general'] = "L'envoi a échoué. Merci de nous écrire directement à "
                    . company_get('email', 'info@europachape.com') . '.';
            }
        }
    }
}

$adresse  = company_get('adresse');
$cp       = company_get('code_postal');
$ville    = company_get('ville');
$tel      = company_get('telephone');
$fax      = company_get('fax');
$email    = company_get('email');
$horaires = company_get('horaires');
$telHref  = preg_replace('/\s+/', '', $tel);
$mapQuery = urlencode(trim("$adresse $cp $ville"));

require __DIR__ . '/partials/header.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">Accueil</a> &rsaquo; Contact</p>
    <h1>Contactez-nous</h1>
    <p>Une question, un projet de chape ? Écrivez-nous : nous vous répondons sous 48 heures.</p>
  </div>
</section>

<section class="section">
  <div class="container contact-grid">
    <!-- Formulaire -->
    <div>
      <h2>Demande de devis</h2>
      <?php if ($sent): ?>
        <p class="alert alert--ok" role="status">Merci pour votre message ! Nous vous répondrons dans les meilleurs délais.</p>
      <?php endif; ?>
      <?php if (!empty($errors['general'])): ?>
        <p class="alert alert--err" role="alert"><?= e($errors['general']) ?></p>
      <?php endif; ?>

      <form class="form" method="post" action="contact.php#main" novalidate style="margin-top:1.2rem">
        <?= csrf_field() ?>
        <!-- Honeypot anti-spam (masqué aux humains) -->
        <div style="position:absolute;left:-9999px" aria-hidden="true">
          <label>Ne pas remplir<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="name">Nom <span class="req">*</span></label>
            <input class="input" type="text" id="name" name="name" required value="<?= e($old['name']) ?>">
            <?php if (!empty($errors['name'])): ?><small class="alert alert--err"><?= e($errors['name']) ?></small><?php endif; ?>
          </div>
          <div class="field">
            <label for="phone">Téléphone</label>
            <input class="input" type="tel" id="phone" name="phone" value="<?= e($old['phone']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="email">E-mail <span class="req">*</span></label>
            <input class="input" type="email" id="email" name="email" required value="<?= e($old['email']) ?>">
            <?php if (!empty($errors['email'])): ?><small class="alert alert--err"><?= e($errors['email']) ?></small><?php endif; ?>
          </div>
          <div class="field">
            <label for="subject">Sujet</label>
            <input class="input" type="text" id="subject" name="subject" value="<?= e($old['subject']) ?>">
          </div>
        </div>

        <div class="field">
          <label for="message">Votre message <span class="req">*</span></label>
          <textarea class="textarea" id="message" name="message" required><?= e($old['message']) ?></textarea>
          <?php if (!empty($errors['message'])): ?><small class="alert alert--err"><?= e($errors['message']) ?></small><?php endif; ?>
        </div>

        <div><button class="btn btn--primary" type="submit">Envoyer ma demande</button></div>
      </form>
    </div>

    <!-- Coordonnées -->
    <div>
      <h2>Nos coordonnées</h2>
      <ul class="info-list" style="margin-top:1.2rem">
        <?php if ($adresse): ?>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
          <span><strong>Adresse</strong><?= e($adresse) ?><br><?= e(trim("$cp $ville")) ?></span>
        </li>
        <?php endif; ?>
        <?php if ($tel): ?>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.4 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
          <span><strong>Téléphone</strong><a href="tel:<?= e($telHref) ?>"><?= e($tel) ?></a><?php if ($fax): ?><br>Fax : <?= e($fax) ?><?php endif; ?></span>
        </li>
        <?php endif; ?>
        <?php if ($email): ?>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
          <span><strong>E-mail</strong><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></span>
        </li>
        <?php endif; ?>
        <?php if ($horaires): ?>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
          <span><strong>Horaires</strong><?= e($horaires) ?></span>
        </li>
        <?php endif; ?>
      </ul>

      <?php if ($mapQuery !== ''): ?>
      <iframe class="map-embed" loading="lazy" title="Localisation d'Europachape"
              src="https://www.google.com/maps?q=<?= e($mapQuery) ?>&output=embed"></iframe>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
