<?php
/**
 * Authentification de l'espace d'administration.
 *
 * Démarre une session sécurisée et fournit les fonctions de connexion,
 * de déconnexion et de protection des pages admin.
 */

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

/** Démarre une session avec des cookies durcis. */
function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? null) == 443);

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('EUROPACHAPE_SESS');
    session_start();
}

/** Tente de connecter un administrateur. Retourne true si succès. */
function admin_login(string $login, string $password): bool
{
    $stmt = db()->prepare('SELECT id, login, password_hash FROM admin_users WHERE login = ? LIMIT 1');
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    // Régénère l'ID de session pour éviter la fixation de session.
    session_regenerate_id(true);
    $_SESSION['admin_id']    = (int) $user['id'];
    $_SESSION['admin_login'] = $user['login'];
    return true;
}

/** Déconnecte l'administrateur courant. */
function admin_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/** Vrai si un administrateur est connecté. */
function is_admin(): bool
{
    return !empty($_SESSION['admin_id']);
}

/** Protège une page : redirige vers la connexion si non authentifié. */
function require_admin(): void
{
    if (!is_admin()) {
        redirect('index.php?redirected=1');
    }
}
