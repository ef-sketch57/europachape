<?php
require_once __DIR__ . '/../includes/auth.php';
start_secure_session();
require_admin();

$root = dirname(__DIR__); // racine du site
$id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$ref  = null;
$images = [];

/** Garantit l'unicité d'un slug (hors enregistrement courant). */
function unique_slug(string $base, int $excludeId): string
{
    $slug = $base; $i = 2;
    $stmt = db()->prepare("SELECT COUNT(*) FROM `references` WHERE slug = ? AND id <> ?");
    while (true) {
        $stmt->execute([$slug, $excludeId]);
        if ((int) $stmt->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $base . '-' . $i++;
    }
}

// --- Enregistrement ---------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int) ($_POST['id'] ?? 0);

    $titre       = trim((string) ($_POST['titre'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $lieu        = trim((string) ($_POST['lieu'] ?? ''));
    $surface     = trim((string) ($_POST['surface'] ?? ''));
    $datePost    = trim((string) ($_POST['date_projet'] ?? ''));
    $published   = isset($_POST['is_published']) ? 1 : 0;
    $date        = ($datePost !== '' && strtotime($datePost)) ? date('Y-m-d', strtotime($datePost)) : null;

    if ($titre === '') {
        flash_set('err', 'Le titre est obligatoire.');
        redirect('reference-edit.php' . ($id ? '?id=' . $id : ''));
    }

    $slug = unique_slug(slugify($titre), $id);
    $pdo  = db();

    if ($id > 0) {
        $stmt = $pdo->prepare(
            "UPDATE `references` SET titre=?, slug=?, description=?, lieu=?, surface=?, date_projet=?, is_published=? WHERE id=?"
        );
        $stmt->execute([$titre, $slug, $description, $lieu, $surface, $date, $published, $id]);
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO `references` (titre, slug, description, lieu, surface, date_projet, is_published) VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->execute([$titre, $slug, $description, $lieu, $surface, $date, $published]);
        $id = (int) $pdo->lastInsertId();
    }

    // Suppression des images cochées.
    if (!empty($_POST['delete_images']) && is_array($_POST['delete_images'])) {
        $sel = $pdo->prepare("SELECT chemin_fichier FROM reference_images WHERE id=? AND reference_id=?");
        $del = $pdo->prepare("DELETE FROM reference_images WHERE id=? AND reference_id=?");
        foreach ($_POST['delete_images'] as $imgId) {
            $imgId = (int) $imgId;
            $sel->execute([$imgId, $id]);
            if ($path = $sel->fetchColumn()) {
                $full = $root . '/' . ltrim((string) $path, '/');
                if (is_file($full) && str_starts_with(realpath($full) ?: '', realpath($root . '/uploads') ?: 'xxx')) {
                    @unlink($full);
                }
            }
            $del->execute([$imgId, $id]);
        }
    }

    // Upload de nouvelles images.
    $ordre = (int) $pdo->query("SELECT COALESCE(MAX(ordre),-1)+1 FROM reference_images WHERE reference_id=" . $id)->fetchColumn();
    $errorsUpload = [];
    if (!empty($_FILES['images']['name'][0])) {
        $ins = $pdo->prepare("INSERT INTO reference_images (reference_id, chemin_fichier, alt, ordre) VALUES (?,?,?,?)");
        $count = count($_FILES['images']['name']);
        for ($i = 0; $i < $count; $i++) {
            $file = [
                'name'     => $_FILES['images']['name'][$i],
                'type'     => $_FILES['images']['type'][$i],
                'tmp_name' => $_FILES['images']['tmp_name'][$i],
                'error'    => $_FILES['images']['error'][$i],
                'size'     => $_FILES['images']['size'][$i],
            ];
            try {
                if ($path = save_uploaded_image($file)) {
                    $ins->execute([$id, $path, $titre, $ordre++]);
                }
            } catch (Throwable $ex) {
                $errorsUpload[] = $file['name'] . ' : ' . $ex->getMessage();
            }
        }
    }

    if ($errorsUpload) {
        flash_set('err', 'Certaines images n\'ont pas pu être ajoutées — ' . implode(' ; ', $errorsUpload));
    }
    flash_set('ok', 'Référence enregistrée.');
    redirect('reference-edit.php?id=' . $id);
}

// --- Chargement pour l'affichage -------------------------------------
if ($id > 0) {
    $stmt = db()->prepare("SELECT * FROM `references` WHERE id = ?");
    $stmt->execute([$id]);
    $ref = $stmt->fetch();
    if (!$ref) {
        flash_set('err', 'Référence introuvable.');
        redirect('references.php');
    }
    $imgStmt = db()->prepare("SELECT * FROM reference_images WHERE reference_id = ? ORDER BY ordre, id");
    $imgStmt->execute([$id]);
    $images = $imgStmt->fetchAll();
}

$v = fn(string $k) => e((string) ($ref[$k] ?? ''));
$admin_title = $id ? 'Modifier une référence' : 'Nouvelle référence';
$admin_nav   = 'references';
require __DIR__ . '/partials/header.php';
?>
<div class="admin-head">
  <h1><?= $id ? 'Modifier la référence' : 'Nouvelle référence' ?></h1>
  <a class="btn btn--ghost" href="references.php">← Retour à la liste</a>
</div>

<form method="post" action="reference-edit.php" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int) $id ?>">

  <div class="panel">
    <div class="field">
      <label for="titre">Titre <span style="color:var(--err)">*</span></label>
      <input class="input" type="text" id="titre" name="titre" required value="<?= $v('titre') ?>">
    </div>
    <div class="row">
      <div class="field">
        <label for="lieu">Lieu</label>
        <input class="input" type="text" id="lieu" name="lieu" placeholder="ex. Luppy (57)" value="<?= $v('lieu') ?>">
      </div>
      <div class="field">
        <label for="surface">Surface</label>
        <input class="input" type="text" id="surface" name="surface" placeholder="ex. 2 700 m²" value="<?= $v('surface') ?>">
      </div>
    </div>
    <div class="row">
      <div class="field">
        <label for="date_projet">Date du projet</label>
        <input class="input" type="date" id="date_projet" name="date_projet" value="<?= $v('date_projet') ?>">
      </div>
      <div class="field">
        <label for="is_published">Statut</label>
        <label style="font-weight:400;display:flex;gap:.5rem;align-items:center;margin-top:.4rem">
          <input type="checkbox" id="is_published" name="is_published" value="1" <?= (!$ref || $ref['is_published']) ? 'checked' : '' ?>>
          Visible sur le site public
        </label>
      </div>
    </div>
    <div class="field">
      <label for="description">Description</label>
      <textarea class="textarea" id="description" name="description" placeholder="Décrivez le chantier, la technique employée, le résultat..."><?= $v('description') ?></textarea>
      <span class="help">Laissez une ligne vide entre deux paragraphes pour les séparer.</span>
    </div>
  </div>

  <div class="panel">
    <h2 style="margin-top:0">Photos</h2>

    <?php if ($images): ?>
      <p class="help">Cochez une photo pour la supprimer lors de l'enregistrement.</p>
      <div class="img-grid">
        <?php foreach ($images as $img): ?>
          <div class="img-card">
            <img src="../<?= e($img['chemin_fichier']) ?>" alt="<?= e($img['alt']) ?>">
            <div class="img-card__foot">
              <label style="display:flex;gap:.35rem;align-items:center;font-size:.85rem;color:var(--err)">
                <input type="checkbox" name="delete_images[]" value="<?= (int) $img['id'] ?>"> Suppr.
              </label>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <hr style="border:none;border-top:1px solid var(--line);margin:1.3rem 0">
    <?php endif; ?>

    <div class="field">
      <label for="images">Ajouter des photos</label>
      <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
      <span class="help">JPEG, PNG ou WebP. Plusieurs fichiers possibles. Les images sont automatiquement redimensionnées et optimisées.</span>
    </div>
  </div>

  <div class="actions">
    <button class="btn btn--primary" type="submit">Enregistrer</button>
    <a class="btn btn--ghost" href="references.php">Annuler</a>
    <?php if ($id): ?><a class="btn btn--ghost" href="../reference.php?slug=<?= e(urlencode($ref['slug'])) ?>" target="_blank" rel="noopener">Aperçu ↗</a><?php endif; ?>
  </div>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
