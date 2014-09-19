<?php

// encode UTF-8
mb_internal_encoding ('UTF-8');

// constantes d'accès a la base de donnée
define('DB_HOST',			'localhost');
define('DB_DATABASE',		'cuisine');
define('DB_USER',			'root');
define('DB_PASSWORD',		'');
define('DB_PREF',			'cuis_');

// liste des pages existantes et de leur titres
$PAGES = array(
	'list'			=> 'Liste des recettes',
	'recette'		=> 'Afficher une recette',
	'edit'			=> 'Editer une recette',
	'panier'		=> 'Panier',
	'ingredients'	=> 'Ingrédients',
	'aide'			=> 'Aide',
);

$UNITES = array(
	'piece'		=> 'pièce',
	'kg'		=> 'kg',
	'g'			=> 'g',
	'pincee'	=> 'pincée',
	'ccs'		=> 'c.c. (s)',
	'css'		=> 'c.s. (s)',
	'cups'		=> 'tasse (s)',
	'L'			=> 'L',
	'mL'		=> 'mL',
	'cL'		=> 'cL',
	'ccl'		=> 'c.c. (l)',
	'csl'		=> 'c.s. (l)',
	'cupl'		=> 'tasse (l)',
);

$UNITES_SHOW = array(
	'piece'		=> 'pièce',
	'kg'		=> 'kg',
	'g'			=> 'g',
	'pincee'	=> 'pincée',
	'ccs'		=> 'c.c.',
	'css'		=> 'c.s.',
	'cups'		=> 'tasse',
	'L'			=> 'L',
	'mL'		=> 'mL',
	'cL'		=> 'cL',
	'ccl'		=> 'c.c.',
	'csl'		=> 'c.s.',
	'cupl'		=> 'tasse',
);

$UNITES_PRINCIPALES = array(
	1	=> 'piece',
	2	=> 'kg',
	3	=> 'L',
);

$UNITES_SECONDAIRES = array(
	1	=> 'piece',
	2	=> 'g',
	3	=> 'mL',
);

$UNITES_RATIO = array(
	'piece'		=> array('groupe' => 1,	'ratio' => '1'),
	
	'kg'		=> array('groupe' => 2,	'ratio' => '1'),
	'g'			=> array('groupe' => 2,	'ratio' => '0.001'),
	'pincee'	=> array('groupe' => 2,	'ratio' => '0.005'),
	'ccs'		=> array('groupe' => 2,	'ratio' => '0.01'),
	'css'		=> array('groupe' => 2,	'ratio' => '0.05'),
	'cups'		=> array('groupe' => 2,	'ratio' => '0.125'),
	
	'L'			=> array('groupe' => 3,	'ratio' => '1'),
	'mL'		=> array('groupe' => 3,	'ratio' => '0.001'),
	'cL'		=> array('groupe' => 3,	'ratio' => '0.01'),
	'ccl'		=> array('groupe' => 3,	'ratio' => '0.03'),
	'csl'		=> array('groupe' => 3,	'ratio' => '0.01'),
	'cupl'		=> array('groupe' => 3,	'ratio' => '0.240'),
);

$NOTES = array(
	'0'		=> '0',
	'1'		=> '1',
	'2'		=> '2',
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5',
	'6'		=> '6',
	'7'		=> '7',
	'8'		=> '8',
	'9'		=> '9',
	'10'	=> '10',
);

$PERSONNES = array(
	'1'		=> '1',
	'2'		=> '2',
	'3'		=> '3',
	'4'		=> '4',
	'5'		=> '5',
	'6'		=> '6',
	'7'		=> '7',
	'8'		=> '8',
	'9'		=> '9',
	'10'	=> '10',
	'11'	=> '11',
	'12'	=> '12',
	'13'	=> '13',
	'14'	=> '14',
	'15'	=> '15',
	'16'	=> '16',
	'17'	=> '17',
	'18'	=> '18',
	'19'	=> '19'
);


?>