<?php
require_once __DIR__ . '/../includes/auth.php';
start_secure_session();
require_admin();

// Champs éditables : clé => [libellé, type]
$fields = [
    'raison_sociale' => ['Raison sociale', 'text'],
    'accroche'       => ['Accroche (slogan)', 'text'],
    'adresse'        => ['Adresse', 'text'],
    'code_postal'    => ['Code postal', 'text'],
    'ville'          => ['Ville', 'text'],
    'telephone'      => ['Téléphone', 'text'],
    'fax'            => ['Fax', 'text'],
    'email'          => ['E-mail', 'email'],
    'horaires'       => ['Horaires', 'text'],
    'facebook'       => ['Lien Facebook', 'url'],
    'instagram'      => ['Lien Instagram', 'url'],
    'linkedin'       => ['Lien LinkedIn', 'url'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pdo = db();
    $stmt = $pdo->prepare(
        "INSERT INTO company_info (cle, valeur) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)"
    );
    foreach ($fields as $key => $_) {
        $value = trim((string) ($_POST[$key] ?? ''));
        $stmt->execute([$key, $value]);
    }
    flash_set('ok', 'Coordonnées mises à jour. Elles sont immédiatement visibles sur le site.');
    redirect('company.php');
}

$data = company();
$admin_title = 'Coordonnées';
$admin_nav   = 'company';
require __DIR__ . '/partials/header.php';
?>
<div class="admin-head">
  <h1>Coordonnées de l'entreprise</h1>
</div>

<form method="post" action="company.php">
  <?= csrf_field() ?>
  <div class="panel">
    <p class="help" style="margin-top:0">Ces informations alimentent l'en-tête, le pied de page et la page Contact du site public.</p>
    <div class="row">
      <?php foreach ($fields as $key => [$label, $type]): ?>
        <div class="field">
          <label for="<?= e($key) ?>"><?= e($label) ?></label>
          <input class="input" type="<?= e($type) ?>" id="<?= e($key) ?>" name="<?= e($key) ?>"
                 value="<?= e((string) ($data[$key] ?? '')) ?>"
                 <?= in_array($key, ['facebook','instagram','linkedin'], true) ? 'placeholder="https://…"' : '' ?>>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <button class="btn btn--primary" type="submit">Enregistrer les coordonnées</button>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
