<?php
/** Pied de page commun des pages publiques. */
$raison    = company_get('raison_sociale', 'Europachape');
$adresse   = company_get('adresse');
$cp        = company_get('code_postal');
$ville     = company_get('ville');
$tel       = company_get('telephone');
$email     = company_get('email');
$horaires  = company_get('horaires');
$facebook  = company_get('facebook');
$instagram = company_get('instagram');
$linkedin  = company_get('linkedin');
$telHref   = preg_replace('/\s+/', '', $tel);
?>
</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <img src="assets/img/logo-blanc.png" alt="Logo <?= e($raison) ?>">
      <p><?= e(company_get('accroche', 'Spécialiste de la chape liquide et traditionnelle.')) ?></p>
    </div>

    <div class="footer-col">
      <h4>Navigation</h4>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="a-propos.php">À propos</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="references.php">Références</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Contact</h4>
      <ul>
        <?php if ($adresse): ?><li><?= e($adresse) ?><br><?= e(trim($cp . ' ' . $ville)) ?></li><?php endif; ?>
        <?php if ($tel): ?><li><a href="tel:<?= e($telHref) ?>"><?= e($tel) ?></a></li><?php endif; ?>
        <?php if ($email): ?><li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li><?php endif; ?>
        <?php if ($horaires): ?><li><?= e($horaires) ?></li><?php endif; ?>
      </ul>
      <?php if ($facebook || $instagram || $linkedin): ?>
      <div class="socials" style="margin-top:1rem">
        <?php if ($facebook): ?>
          <a href="<?= e($facebook) ?>" aria-label="Facebook" rel="noopener" target="_blank">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 9h3l.5-3H14V4.5c0-.9.3-1.5 1.6-1.5H17V.2C16.6.1 15.6 0 14.4 0 11.9 0 10 1.5 10 4.3V6H7v3h3v9h4z"/></svg>
          </a>
        <?php endif; ?>
        <?php if ($instagram): ?>
          <a href="<?= e($instagram) ?>" aria-label="Instagram" rel="noopener" target="_blank">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
          </a>
        <?php endif; ?>
        <?php if ($linkedin): ?>
          <a href="<?= e($linkedin) ?>" aria-label="LinkedIn" rel="noopener" target="_blank">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM.5 8h4V24h-4zM8 8h3.8v2.2h.05c.53-1 1.83-2.2 3.77-2.2C19.6 8 21 10.1 21 14v10h-4v-8.6c0-2-.7-3.4-2.5-3.4-1.36 0-2.17.92-2.53 1.8-.13.32-.16.76-.16 1.2V24H8z"/></svg>
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="footer-bottom container">
    <span>&copy; <?= date('Y') ?> <?= e($raison) ?>. Tous droits réservés.</span>
    <span><a href="admin/index.php">Espace administration</a></span>
  </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
