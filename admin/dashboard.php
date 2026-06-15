<?php
require_once __DIR__ . '/../includes/auth.php';
start_secure_session();
require_admin();

// --- Changement de mot de passe --------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'password') {
    csrf_check();
    $current = (string) ($_POST['current'] ?? '');
    $new     = (string) ($_POST['new'] ?? '');
    $confirm = (string) ($_POST['confirm'] ?? '');

    $stmt = db()->prepare('SELECT password_hash FROM admin_users WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $hash = (string) $stmt->fetchColumn();

    if (!password_verify($current, $hash)) {
        flash_set('err', 'Le mot de passe actuel est incorrect.');
    } elseif (strlen($new) < 8) {
        flash_set('err', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
    } elseif ($new !== $confirm) {
        flash_set('err', 'La confirmation ne correspond pas au nouveau mot de passe.');
    } else {
        $upd = db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?');
        $upd->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
        flash_set('ok', 'Votre mot de passe a été modifié.');
    }
    redirect('dashboard.php');
}

// --- Statistiques rapides --------------------------------------------
$nbRefs = (int) db()->query("SELECT COUNT(*) FROM `references`")->fetchColumn();
$nbPub  = (int) db()->query("SELECT COUNT(*) FROM `references` WHERE is_published = 1")->fetchColumn();
$nbImg  = (int) db()->query("SELECT COUNT(*) FROM reference_images")->fetchColumn();

$admin_title = 'Tableau de bord';
$admin_nav   = 'dashboard';
require __DIR__ . '/partials/header.php';
?>
<div class="admin-head">
  <h1>Bonjour, <?= e($_SESSION['admin_login']) ?> 👋</h1>
  <a class="btn btn--primary" href="reference-edit.php">+ Nouvelle référence</a>
</div>

<div class="panel">
  <div class="row" style="grid-template-columns:repeat(3,1fr)">
    <div><div style="font-size:2rem;font-weight:800;color:var(--brand)"><?= $nbRefs ?></div><div class="help">Références au total</div></div>
    <div><div style="font-size:2rem;font-weight:800;color:var(--brand)"><?= $nbPub ?></div><div class="help">Publiées sur le site</div></div>
    <div><div style="font-size:2rem;font-weight:800;color:var(--brand)"><?= $nbImg ?></div><div class="help">Photos</div></div>
  </div>
</div>

<div class="panel">
  <h2 style="margin-top:0">Accès rapides</h2>
  <div class="actions">
    <a class="btn btn--ghost" href="references.php">Gérer les références</a>
    <a class="btn btn--ghost" href="company.php">Modifier les coordonnées</a>
    <a class="btn btn--ghost" href="../index.php" target="_blank" rel="noopener">Voir le site ↗</a>
  </div>
</div>

<div class="panel">
  <h2 style="margin-top:0">Changer mon mot de passe</h2>
  <form method="post" action="dashboard.php" style="max-width:420px">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="password">
    <div class="field">
      <label for="current">Mot de passe actuel</label>
      <input class="input" type="password" id="current" name="current" autocomplete="current-password" required>
    </div>
    <div class="field">
      <label for="new">Nouveau mot de passe</label>
      <input class="input" type="password" id="new" name="new" autocomplete="new-password" required>
      <span class="help">8 caractères minimum.</span>
    </div>
    <div class="field">
      <label for="confirm">Confirmer le nouveau mot de passe</label>
      <input class="input" type="password" id="confirm" name="confirm" autocomplete="new-password" required>
    </div>
    <button class="btn btn--dark" type="submit">Mettre à jour</button>
  </form>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
