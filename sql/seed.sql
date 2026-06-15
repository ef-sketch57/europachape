-- =====================================================================
--  Europachape — Données initiales (seed)
--  À importer APRÈS install.sql.
--
--  Compte admin par défaut :
--      identifiant : admin
--      mot de passe : Europachape2026!
--  >>> À CHANGER IMMÉDIATEMENT après la première connexion (menu Admin). <<<
-- =====================================================================

SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
--  Administrateur par défaut
-- ---------------------------------------------------------------------
INSERT INTO `admin_users` (`login`, `password_hash`) VALUES
  ('admin', '$2y$12$7D5yrwseOwv9wg3AeoRyG.BAhdcwC5DO376efpZyLpRp7iBVhvzS2')
ON DUPLICATE KEY UPDATE `login` = `login`;

-- ---------------------------------------------------------------------
--  Coordonnées de l'entreprise
-- ---------------------------------------------------------------------
INSERT INTO `company_info` (`cle`, `valeur`) VALUES
  ('raison_sociale', 'Europachape'),
  ('accroche',       'Votre spécialiste de la chape liquide et traditionnelle'),
  ('adresse',        '48, route de Metz'),
  ('code_postal',    '57100'),
  ('ville',          'Thionville'),
  ('telephone',      '03 82 58 46 18'),
  ('fax',            '03 82 58 47 09'),
  ('email',          'info@europachape.com'),
  ('horaires',       'Du lundi au vendredi : 8h00 – 18h00'),
  ('facebook',       ''),
  ('instagram',      ''),
  ('linkedin',       '')
ON DUPLICATE KEY UPDATE `valeur` = VALUES(`valeur`);

-- ---------------------------------------------------------------------
--  Références d'exemple (tirées du contenu existant)
-- ---------------------------------------------------------------------
INSERT INTO `references` (`id`, `titre`, `slug`, `description`, `lieu`, `surface`, `date_projet`, `is_published`) VALUES
  (1, 'Chape fluide — Luppy', 'chape-fluide-luppy',
   'Réalisation d''une chape liquide autonivelante sur un chantier résidentiel à Luppy. Mise en œuvre par pompage pour un support parfaitement plan, prêt à recevoir le revêtement final dans les meilleurs délais.',
   'Luppy (57)', '2 700 m²', '2019-05-10', 1),
  (2, 'Chape industrielle — Garage automobile', 'chape-industrielle-garage',
   'Coulage d''une chape industrielle haute résistance pour un garage automobile. Une solution durable et facile d''entretien, adaptée au passage intensif de véhicules.',
   'Moselle (57)', '3 800 m²', '2020-09-22', 1),
  (3, 'Chape liquide — Bâtiment industriel', 'chape-liquide-batiment-industriel',
   'Grand chantier de chape liquide autonivelante en milieu industriel. Notre équipement (camion à chape, machines à pelle) nous a permis de couvrir une grande surface tout en respectant les délais.',
   'Région Est', '4 000 m²', '2021-03-15', 1)
ON DUPLICATE KEY UPDATE `titre` = VALUES(`titre`);

-- ---------------------------------------------------------------------
--  Images des références d'exemple
-- ---------------------------------------------------------------------
INSERT INTO `reference_images` (`reference_id`, `chemin_fichier`, `alt`, `ordre`) VALUES
  (1, 'uploads/projet-luppy-1.jpg',      'Chantier de chape fluide à Luppy', 0),
  (1, 'uploads/projet-luppy-2.jpg',      'Coulage de la chape liquide',      1),
  (1, 'uploads/projet-luppy-3.jpg',      'Finition autonivelante',           2),
  (2, 'uploads/projet-garage-1.jpg',     'Chape industrielle de garage',     0),
  (2, 'uploads/projet-garage-2.jpg',     'Surface finie haute résistance',   1),
  (3, 'uploads/projet-industriel-1.jpg', 'Chape liquide en bâtiment industriel', 0),
  (3, 'uploads/projet-industriel-2.jpg', 'Pompage de la chape',              1),
  (3, 'uploads/projet-industriel-3.jpg', 'Grande surface coulée',            2);
