<?php
require_once __DIR__ . '/../includes/auth.php';
start_secure_session();
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('references.php');
}
csrf_check();

$id   = (int) ($_POST['id'] ?? 0);
$root = dirname(__DIR__);

if ($id > 0) {
    $pdo = db();

    // Supprime les fichiers images du disque (dans /uploads uniquement).
    $stmt = $pdo->prepare("SELECT chemin_fichier FROM reference_images WHERE reference_id = ?");
    $stmt->execute([$id]);
    $uploadsReal = realpath($root . '/uploads') ?: '';
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $path) {
        $full = $root . '/' . ltrim((string) $path, '/');
        $real = realpath($full) ?: '';
        if ($real !== '' && $uploadsReal !== '' && str_starts_with($real, $uploadsReal) && is_file($full)) {
            @unlink($full);
        }
    }

    // La contrainte ON DELETE CASCADE supprime aussi les lignes images.
    $del = $pdo->prepare("DELETE FROM `references` WHERE id = ?");
    $del->execute([$id]);
    flash_set('ok', 'Référence supprimée.');
}

redirect('references.php');
