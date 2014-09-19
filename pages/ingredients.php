<?php

$T['js_out'] = 'ko';
$T['js_file'] = 'ingredients';
$T['jquery'] = true;

// del si del ou suppression de toutes les personnes au moins
if ($js && !empty($_GET['del']) && ($id = (int) $_GET['del']))
{
	if (MySQL::getRow('SELECT id FROM '.DB_PREF.'ingredients WHERE id = :1', $id))
	{
		if ($used = MySQL::query('SELECT id FROM '.DB_PREF.'details WHERE id_ingredient = :1', $id))
		{
			if (isset($_GET['force']))
			{
				// array des trucs a suppr
				$todel = array();
				foreach($used as $u) $todel[] = $u['id'];
				
				MySQL::query('DELETE FROM '.DB_PREF.'details WHERE id IN ('.implode(',',$todel).')');
				MySQL::query('DELETE FROM '.DB_PREF.'ingredients WHERE id = :1', $id);
				$T['js_out'] = 'ok';
			}
			else
				$T['js_out'] = 'used';
		}
		else
		{
			MySQL::query('DELETE FROM '.DB_PREF.'ingredients WHERE id = :1', $id);
			$T['js_out'] = 'ok';
		}
	}
	else
		$T['js_out'] = 'ko';
}

// add
elseif ($js && !empty($_GET['add']) && !MySQL::getRow('SELECT id FROM '.DB_PREF.'ingredients WHERE nom = :1', $_GET['add']))
{
	MySQL::insertRow(DB_PREF.'ingredients', array('nom' => $_GET['add']));
	$T['js_out'] = MySQL::insertId();
}

// liste
elseif (!$js)
{
	$T['ingredients'] = get_ingredients();
}