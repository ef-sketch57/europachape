<?php
/**
 * Fonctions utilitaires partagées (échappement, CSRF, données société,
 * traitement des images, etc.).
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';

/** Échappe une chaîne pour un affichage HTML sûr (anti-XSS). */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Redirige vers une URL puis stoppe le script. */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/** Transforme un titre en slug URL (ex. "Chape fluide" -> "chape-fluide"). */
function slugify(string $text): string
{
    $text = (string) preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = @iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text;
    $text = (string) preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = (string) preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text !== '' ? $text : 'projet';
}

// ---------------------------------------------------------------------
//  Coordonnées de l'entreprise (table company_info)
// ---------------------------------------------------------------------

/** Charge toutes les infos société sous forme de tableau clé => valeur. */
function company(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    try {
        $rows = db()->query('SELECT cle, valeur FROM company_info')->fetchAll();
        foreach ($rows as $row) {
            $cache[$row['cle']] = $row['valeur'];
        }
    } catch (Throwable $e) {
        $cache = [];
    }
    return $cache;
}

/** Récupère une info société avec valeur de repli. */
function company_get(string $key, string $default = ''): string
{
    $data = company();
    return isset($data[$key]) && $data[$key] !== null ? (string) $data[$key] : $default;
}

// ---------------------------------------------------------------------
//  Protection CSRF (jetons de formulaire)
// ---------------------------------------------------------------------

/** Retourne le jeton CSRF de la session (le crée si besoin). */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Champ caché à insérer dans chaque formulaire protégé. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** Vérifie le jeton CSRF reçu ; coupe la requête si invalide. */
function csrf_check(): void
{
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('Session expirée ou requête invalide. Merci de recharger la page.');
    }
}

// ---------------------------------------------------------------------
//  Messages "flash" (affichés une fois après une redirection)
// ---------------------------------------------------------------------

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_all(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

// ---------------------------------------------------------------------
//  Traitement des images uploadées (redimensionnement + optimisation)
// ---------------------------------------------------------------------

/**
 * Enregistre une image uploadée dans /uploads après redimensionnement.
 *
 * @param array  $file     Entrée de $_FILES (un seul fichier).
 * @param int    $maxWidth Largeur maximale en pixels.
 * @return string|null      Chemin relatif (ex. "uploads/xxx.jpg") ou null.
 * @throws RuntimeException En cas de fichier invalide.
 */
function save_uploaded_image(array $file, int $maxWidth = 1600): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Échec de l\'upload (code ' . $file['error'] . ').');
    }
    if (!is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Fichier invalide.');
    }

    // Vérifie qu'il s'agit bien d'une image et récupère ses dimensions.
    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        throw new RuntimeException('Le fichier n\'est pas une image valide.');
    }
    [$width, $height] = $info;
    $mime = $info['mime'];

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format non supporté (JPEG, PNG ou WebP uniquement).');
    }

    // Crée l'image source selon le type.
    $src = match ($mime) {
        'image/jpeg' => imagecreatefromjpeg($file['tmp_name']),
        'image/png'  => imagecreatefrompng($file['tmp_name']),
        'image/webp' => imagecreatefromwebp($file['tmp_name']),
    };
    if (!$src) {
        throw new RuntimeException('Impossible de lire l\'image.');
    }

    // Calcule les nouvelles dimensions (ne jamais agrandir).
    $ratio     = $width > $maxWidth ? $maxWidth / $width : 1.0;
    $newWidth  = (int) round($width * $ratio);
    $newHeight = (int) round($height * $ratio);

    $dst = imagecreatetruecolor($newWidth, $newHeight);
    // Préserve la transparence pour PNG / WebP.
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Nom de fichier unique.
    $ext      = $allowed[$mime];
    $filename = 'photo-' . date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
    $uploadDir = dirname(__DIR__) . '/uploads';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }
    $target = $uploadDir . '/' . $filename;

    $ok = match ($mime) {
        'image/jpeg' => imagejpeg($dst, $target, 82),
        'image/png'  => imagepng($dst, $target, 6),
        'image/webp' => imagewebp($dst, $target, 82),
    };

    imagedestroy($src);
    imagedestroy($dst);

    if (!$ok) {
        throw new RuntimeException('Impossible d\'enregistrer l\'image.');
    }

    return 'uploads/' . $filename;
}
