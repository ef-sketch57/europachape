<?php
/** Sitemap XML dynamique (pages fixes + références publiées). */
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=UTF-8');

global $config;
$base = rtrim($config['site']['base_url'] ?? '', '/');

$urls = [
    ['', '1.0'],
    ['a-propos.php', '0.7'],
    ['services.php', '0.9'],
    ['references.php', '0.8'],
    ['contact.php', '0.8'],
];

$refs = [];
try {
    $refs = db()->query("SELECT slug, updated_at FROM `references` WHERE is_published = 1")->fetchAll();
} catch (Throwable $e) { /* base non configurée */ }

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($urls as [$path, $prio]) {
    echo "  <url><loc>{$base}/{$path}</loc><priority>{$prio}</priority></url>\n";
}
foreach ($refs as $r) {
    $loc = $base . '/reference.php?slug=' . urlencode($r['slug']);
    $lastmod = !empty($r['updated_at']) ? date('Y-m-d', strtotime($r['updated_at'])) : date('Y-m-d');
    echo "  <url><loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc><lastmod>{$lastmod}</lastmod><priority>0.6</priority></url>\n";
}

echo '</urlset>';
