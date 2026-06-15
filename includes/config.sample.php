<?php
/**
 * Configuration du site Europachape.
 *
 *  >>> COPIEZ ce fichier en "config.php" (dans le même dossier /includes)
 *      puis renseignez vos identifiants IONOS ci-dessous. <<<
 *
 *  Le fichier config.php n'est PAS versionné (voir .gitignore) : vos
 *  identifiants restent privés et ne sont jamais publiés dans le dépôt.
 */

return [

    // ---------------------------------------------------------------
    //  Base de données MySQL / MariaDB (IONOS)
    //  L'hôte n'est PAS "localhost" : copiez le nom d'hôte fourni par
    //  IONOS dans votre espace client (ex. db5012345678.hosting-data.io).
    // ---------------------------------------------------------------
    'db' => [
        'host'    => 'VOTRE_HOTE_IONOS',     // ex. db1234567890.hosting-data.io
        'name'    => 'VOTRE_NOM_DE_BASE',    // ex. dbs1234567
        'user'    => 'VOTRE_UTILISATEUR',    // ex. dbu1234567
        'pass'    => 'VOTRE_MOT_DE_PASSE',
        'charset' => 'utf8mb4',
    ],

    // ---------------------------------------------------------------
    //  Envoi des e-mails (formulaire de contact)
    // ---------------------------------------------------------------
    'mail' => [
        // Adresse qui reçoit les messages du formulaire de contact.
        'to'      => 'info@europachape.com',
        // Adresse expéditeur : DOIT appartenir à votre domaine IONOS,
        // sinon les mails risquent d'être bloqués / classés en spam.
        'from'    => 'no-reply@europachape.com',
        'subject' => 'Nouveau message depuis le site Europachape',
    ],

    // ---------------------------------------------------------------
    //  Réglages généraux
    // ---------------------------------------------------------------
    'site' => [
        // URL publique du site, sans slash final (utile pour le sitemap).
        'base_url' => 'https://www.europachape.com',
        // Passez à false en production pour masquer les erreurs PHP.
        'debug'    => false,
    ],
];
