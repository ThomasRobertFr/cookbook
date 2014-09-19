<?php


// id
$id = (int) $_GET['id'];

// on sélectionne la recette
$T['recette'] = MySQL::getRow('SELECT * FROM '.DB_PREF.'recettes WHERE id = :1', $id);

// si inexistante
if (empty($T['recette']))
	$T['fatal_error'] = 'Recette introuvable.';
else
{
	$T['jquery'] = true;
	$T['js_file'] = 'recette';
	
	$T['titre'] = $T['recette']['titre'];
	$T['hide_titre'] = true;
	
	// recette

	$T['recette']['recette'] = str_replace("\r", '', $T['recette']['recette']);								// suppr des \r
	$T['recette']['recette'] = '<ul>'.$T['recette']['recette'].'</li></ul>';								// wrapping
	$T['recette']['recette'] = preg_replace("#(\n)? ?\* ?#is", '</li><li>', $T['recette']['recette']);		// * -> <li>
	$T['recette']['recette'] = preg_replace_callback('#(\n)?===(.+)===#isU', create_function('$matches',
	'return "</li></ul><h3>".trim($matches[2])."</h3><ul>";'), $T['recette']['recette']); 		// === titre === => <h3>titre</h3>
	$T['recette']['recette'] = str_replace('<ul></li>', '<ul>', $T['recette']['recette']);					// suppr </li> en trop
	$T['recette']['recette'] = str_replace('<ul></ul>', '', $T['recette']['recette']);						// suppr <ul> vides
	$T['recette']['recette'] = nl2br($T['recette']['recette']);												// nl2br

	// ingredients
	$T['ingredients'] = MySQL::query('
	SELECT '.DB_PREF.'details.mesure, '.DB_PREF.'details.unite, '.DB_PREF.'ingredients.nom
	FROM '.DB_PREF.'details
	JOIN '.DB_PREF.'ingredients ON '.DB_PREF.'details.id_ingredient = '.DB_PREF.'ingredients.id
	WHERE id_recette = :1
	ORDER BY nom ASC
	', $id);
	
	$T['pers'] = 0;
	if (!empty($_GET['pers']) && $T['pers'] = (int) $_GET['pers'])
		foreach($T['ingredients'] as $k => $i)
			convertir_personnes($T['ingredients'][$k]['mesure'], $T['recette']['personnes'], $T['pers']);
}

