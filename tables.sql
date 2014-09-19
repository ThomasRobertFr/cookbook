CREATE TABLE IF NOT EXISTS `cuis_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_recette` smallint(5) unsigned NOT NULL,
  `id_ingredient` mediumint(8) unsigned NOT NULL,
  `mesure` float unsigned NOT NULL,
  `unite` enum('piece','pincee','g','kg','ccs','css','ccl','csl','mL','L','cL','cups','cupl') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `cuis_ingredients` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `cuis_recettes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `dir` tinyint(1) NOT NULL,
  `parent` smallint(5) unsigned NOT NULL,
  `note` tinyint(3) unsigned NOT NULL,
  `duree` smallint(5) unsigned NOT NULL,
  `personnes` tinyint(3) unsigned NOT NULL,
  `description` text NOT NULL,
  `recette` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

