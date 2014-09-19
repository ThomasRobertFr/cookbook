<?php

$T['jquery'] = true;
$T['js_file'] = 'list';
$T['body_class'] = 'large';
$T['hide_titre'] = true;

// affichage du panier
if(isset($_GET['panier']))
{
	$T['titre'] = 'Panier';
	$T['panier'] = true;
	
	// on sélectionne la recette
	$T['recettes'] = (sizeof($PANIER) != 0) ? MySQL::query('SELECT id, titre, note, duree, personnes FROM '.DB_PREF.'recettes WHERE id IN ('.implode_panier($PANIER).') ORDER BY id DESC') : array();

	$ingredients = (sizeof($PANIER) != 0) ? MySQL::query('SELECT mesure, unite, id_ingredient, id_recette FROM '.DB_PREF.'details WHERE id_recette IN ('.implode_panier($PANIER).') ORDER BY id_ingredient ASC') : array();
	$ingredients_sum = array();

	$recettes = array();

	// nombre de personnes par recette
	foreach($T['recettes'] as $r)
	{
		$recettes[$r['id']] = $r['personnes'];
	}

	// cumul des ingredients
	foreach($ingredients as $i)
	{
		convertir_personnes($i['mesure'], $recettes[$i['id_recette']], $PANIER[$i['id_recette']]);
		convertir_mesure($i['mesure'], $i['unite']);
		
		if (!isset($ingredients_sum[$i['id_ingredient']][$i['unite']]))
			$ingredients_sum[$i['id_ingredient']][$i['unite']] = 0;
		
		$ingredients_sum[$i['id_ingredient']][$i['unite']] += $i['mesure'];
	}


	$T['all_ingredients'] = get_ingredients();
	$T['ingredients'] = $ingredients_sum;

}

// liste complete
else
{
	$recettes = MySQL::query('SELECT id, dir, parent, titre, note, duree, personnes FROM '.DB_PREF.'recettes ORDER BY titre ASC'); // LIMIT 0,5');
	$childs = array();
	$T['recettes'] = array();
	
	foreach($recettes as $r)
	{
		// si a parent, dans les enfants, sinon, a la racine
		if ($r['parent'])
			$childs[$r['id']] = $r;
		else
			$T['recettes'][$r['id']] = $r;
	}
	
	foreach($childs as $id => $r)
	{
		if (isset($T['recettes'][$r['parent']]))
			$T['recettes'][$r['parent']]['recettes'][$id] = $r;
		else {
			$r['titre'] .= ' /!\ Parent disparu';
			$T['recettes'][$id] = $r;
		}
	}
}
