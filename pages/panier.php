<?php

$T['js_out'] = 'ko';

// del si del ou suppression de toutes les personnes au moins
if (!empty($_GET['del']) && isset($PANIER[$_GET['del']]) || !empty($_GET['rem']) && !empty($_GET['pers']) && ($pers = (int) $_GET['pers']) && isset($PANIER[$_GET['rem']]) && $PANIER[$_GET['rem']] <= $_GET['pers'])
{
	unset($PANIER[  (!empty($_GET['del'])) ? $_GET['del'] : $_GET['rem']  ]);
	$T['js_out'] = 'ok';
}

// remove de x personnes
elseif (!empty($_GET['rem']) && !empty($_GET['pers']) && ($pers = (int) $_GET['pers']) && isset($PANIER[$_GET['rem']]))
{
	$PANIER[$_GET['rem']] -= $pers;
	$T['js_out'] = 'ok';
}


// add
elseif (!empty($_GET['add']) && !empty($_GET['pers']) && ($pers = (int) $_GET['pers']) && !isset($PANIER[$_GET['add']]))
{
	$id = (int) $_GET['add'];
	if (MySQL::getRow('SELECT id FROM '.DB_PREF.'recettes WHERE id = :1', $id))
	{
		$PANIER[$id] = $pers;
		$T['js_out'] = 'ok';
	}
}

// add maj
elseif (!empty($_GET['add']) && !empty($_GET['pers']) && ($pers = (int) $_GET['pers']) && isset($PANIER[$_GET['add']]))
{
	$PANIER[$_GET['add']] += $pers;
	$T['js_out'] = 'ok';
}

// edit
elseif (!empty($_GET['mod']) && !empty($_GET['pers']) && ($pers = (int) $_GET['pers']) && isset($PANIER[$_GET['mod']]))
{
	$PANIER[$_GET['mod']] = $pers;
	$T['js_out'] = 'ok';
}

// vider
elseif (isset($_GET['clean']))
{
	$PANIER = array();
	$T['js_out'] = 'ok';
}

if(!$js)
{
	header('HTTP/1.1 307 Temporary Redirect');
	header('Location: panier.html');
}