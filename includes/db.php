<?php
/**
 * Connexion à la base de données (PDO) + chargement de la configuration.
 *
 * Ce fichier expose :
 *   - $config : tableau de configuration ;
 *   - db()    : retourne l'instance PDO (connexion paresseuse, partagée).
 */

declare(strict_types=1);

// --- Chargement de la configuration -----------------------------------
$configFile = __DIR__ . '/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Configuration manquante : copiez includes/config.sample.php en includes/config.php.');
}

/** @var array $config */
$config = require $configFile;

// --- Affichage des erreurs selon le mode debug ------------------------
if (!empty($config['site']['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
    ini_set('display_errors', '0');
}

/**
 * Définit une instance PDO à utiliser (utile pour les tests ou un autre SGBD).
 */
function db_set(PDO $pdo): void
{
    $GLOBALS['__pdo'] = $pdo;
}

/**
 * Retourne l'instance PDO partagée (connexion à la demande).
 */
function db(): PDO
{
    if (isset($GLOBALS['__pdo']) && $GLOBALS['__pdo'] instanceof PDO) {
        return $GLOBALS['__pdo'];
    }

    global $config;
    $db = $config['db'];

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $db['host'],
        $db['name'],
        $db['charset'] ?? 'utf8mb4'
    );

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        if (!empty($config['site']['debug'])) {
            exit('Erreur de connexion à la base : ' . $e->getMessage());
        }
        exit('Le site est momentanément indisponible. Merci de réessayer plus tard.');
    }

    $GLOBALS['__pdo'] = $pdo;
    return $pdo;
}
