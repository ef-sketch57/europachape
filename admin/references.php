<?php
require_once __DIR__ . '/../includes/auth.php';
start_secure_session();
require_admin();

$rows = db()->query(
    "SELECT r.id, r.titre, r.lieu, r.surface, r.is_published, r.date_projet,
            (SELECT chemin_fichier FROM reference_images i WHERE i.reference_id = r.id ORDER BY i.ordre, i.id LIMIT 1) AS image,
            (SELECT COUNT(*) FROM reference_images i WHERE i.reference_id = r.id) AS nb_img
     FROM `references` r
     ORDER BY r.date_projet DESC, r.id DESC"
)->fetchAll();

$admin_title = 'Références';
$admin_nav   = 'references';
require __DIR__ . '/partials/header.php';
?>
<div class="admin-head">
  <h1>Références</h1>
  <a class="btn btn--primary" href="reference-edit.php">+ Nouvelle référence</a>
</div>

<div class="panel">
  <?php if (!$rows): ?>
    <p>Aucune référence pour le moment. <a href="reference-edit.php">Créez la première</a>.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr><th></th><th>Titre</th><th>Lieu</th><th>Photos</th><th>Statut</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><img class="thumb" src="../<?= e($r['image'] ?: 'assets/img/hero-2.jpg') ?>" alt=""></td>
            <td><strong><?= e($r['titre']) ?></strong><?php if ($r['surface']): ?><br><span class="help"><?= e($r['surface']) ?></span><?php endif; ?></td>
            <td><?= e($r['lieu'] ?: '—') ?></td>
            <td><?= (int) $r['nb_img'] ?></td>
            <td>
              <?php if ($r['is_published']): ?><span class="badge badge--on">Publiée</span>
              <?php else: ?><span class="badge badge--off">Brouillon</span><?php endif; ?>
            </td>
            <td>
              <div class="actions">
                <a class="btn btn--ghost btn--sm" href="reference-edit.php?id=<?= (int) $r['id'] ?>">Modifier</a>
                <form method="post" action="reference-delete.php" onsubmit="return confirm('Supprimer définitivement cette référence et ses photos ?');" style="display:inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                  <button class="btn btn--danger btn--sm" type="submit">Supprimer</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
