<?php
/** Page de connexion à l'espace d'administration. */
require_once __DIR__ . '/../includes/auth.php';
start_secure_session();

// Déjà connecté : aller au tableau de bord.
if (is_admin()) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $login = trim((string) ($_POST['login'] ?? ''));
    $pass  = (string) ($_POST['password'] ?? '');

    if ($login === '' || $pass === '') {
        $error = 'Merci de renseigner votre identifiant et votre mot de passe.';
    } elseif (admin_login($login, $pass)) {
        redirect('dashboard.php');
    } else {
        // Petite temporisation pour ralentir le bruteforce.
        usleep(400000);
        $error = 'Identifiant ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Connexion — Europachape Admin</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="login-screen">
  <div class="login-card">
    <h1>Espace administration</h1>
    <p class="sub">Connectez-vous pour gérer le site Europachape.</p>

    <?php if ($error): ?><div class="alert alert--err"><?= e($error) ?></div><?php endif; ?>
    <?php if (isset($_GET['redirected'])): ?><div class="alert alert--err">Veuillez vous connecter pour accéder à cette page.</div><?php endif; ?>

    <form method="post" action="index.php">
      <?= csrf_field() ?>
      <div class="field">
        <label for="login">Identifiant</label>
        <input class="input" type="text" id="login" name="login" autocomplete="username" required autofocus>
      </div>
      <div class="field">
        <label for="password">Mot de passe</label>
        <input class="input" type="password" id="password" name="password" autocomplete="current-password" required>
      </div>
      <button class="btn btn--primary" type="submit" style="width:100%;justify-content:center">Se connecter</button>
    </form>
  </div>
</div>
</body>
</html>
