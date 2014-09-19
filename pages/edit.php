<?php

$T['hide_titre'] = true;
$T['body_class'] = 'edit';
$T['jquery'] = true;
$T['js_file'] = 'edit';
$T['js_out'] = 'ko';

/////////////////////////
// INGREDIENTS

if (!$js)
	$T['all_ingredients'] = get_ingredients();

/////////////////////////
// DELETE
if (isset($_GET['del']) && !empty($_GET['id']) && $id = (int) $_GET['id'])
{
	$childs = MySQL::query('SELECT id FROM '.DB_PREF.'recettes WHERE parent = :1', $id);
	if (!empty($childs))
		$T['fatal_error'] = 'Impossible de supprimer un dossier ayant des enfants';
	else
	{
		MySQL::getRow('DELETE FROM '.DB_PREF.'recettes WHERE id = :1', $id);
		MySQL::getRow('DELETE FROM '.DB_PREF.'details WHERE id_recette = :1', $id);
		
		header('HTTP/1.1 307 Temporary Redirect');
		header('Location: liste.html');
	}
}

/////////////////////////
// UPODAD IMAGE

elseif (!empty($_FILES['fic']) && !empty($_GET['id']) && $id = (int) $_GET['id'])
{
	// deplacement fichier
	$file_temp = 'images/'.$id.'.tmp';
	move_uploaded_file($_FILES['fic']['tmp_name'], $file_temp);
	
	// infos img
	list($src_width, $src_height, $src_type) = getimagesize($file_temp);
	
	// creation image
	if ($src_type == IMAGETYPE_GIF) {
		$src = imagecreatefromgif($file_temp); $T['js_out'] = 'ok';
	} elseif ($src_type == IMAGETYPE_JPEG) {
		$src = imagecreatefromjpeg($file_temp); $T['js_out'] = 'ok';
	} elseif ($src_type == IMAGETYPE_BMP) {
		$src = imagecreatefrombmp($file_temp); $T['js_out'] = 'ok';
	} elseif ($src_type == IMAGETYPE_PNG) {
		$src = imagecreatefrompng($file_temp); $T['js_out'] = 'ok';
	}
	
	if ($T['js_out'] == 'ok')
	{
		//// large en jpg
		imagejpeg($src, 'images/'.$id.'_large.jpg', 98);
	
		//// petite en jpg
		$small = imagecreatetruecolor(200, 200);
		$fond = imagecolorallocate($small, 255, 255, 255);
		imagefill($small, 0, 0, $fond); 
		
		if ($src_width > $src_height)
		{
			$src_s = $src_height;
			$src_x = floor(($src_width - $src_height)/2);
			$src_y = 0;
		}
		else
		{
			$src_s = $src_width;
			$src_x = 0;
			$src_y = floor(($src_height - $src_width)/2);
		}
		
		// imagecopyresampled($small, $src, $dst_x,	$dst_y,	$src_x,	$src_y,	$dst_w,	$dst_h,	$src_w,	$src_h)
		imagecopyresampled   ($small, $src, 0,		0,		$src_x,	$src_y,	200,	200,	$src_s,	$src_s);
		imagejpeg($small, 'images/'.$id.'.jpg', 98);
		
		//// destroy
		imagedestroy($src);
	}
	
	// suppr temp file
	unlink($file_temp);
}


/////////////////////////
// COPIE RECETTE
elseif (!empty($_GET['copy']) && $id_copy = (int) $_GET['copy'])
{
	// chargement de la recette a copier
	$T['recette'] = MySQL::getRow('SELECT * FROM '.DB_PREF.'recettes WHERE id = :1', $id_copy);
	
	// si existante, on la copie dans la base
	if($T['recette'])
	{
		// recette
		if ($T['recette']['dir']) $parent = $id_copy;
		else $parent = $T['recette']['parent'];
		
		MySQL::query('INSERT INTO '.DB_PREF.'recettes SELECT \'\', titre, 0, '.$parent.', note, duree, personnes, description, recette FROM '.DB_PREF.'recettes WHERE id = :1', $id_copy);
		$id = MySQL::insertId();
		$T['recette']['id'] = $id;
		$T['recette']['dir'] = 0;
		$T['recette']['parent'] = $parent;
		
		// ingredients
		MySQL::query('INSERT INTO '.DB_PREF.'details SELECT \'\', '.$id.', id_ingredient, mesure, unite FROM '.DB_PREF.'details WHERE id_recette = :1', $id_copy);
		$T['ingredients'] = MySQL::query('SELECT id, id_ingredient, mesure, unite FROM '.DB_PREF.'details WHERE id_recette = :1', $id);
		
		//images
		copy('images/'.$id_copy.'.jpg', 'images/'.$id.'.jpg');
		copy('images/'.$id_copy.'_large.jpg', 'images/'.$id.'_large.jpg');
	}
	
	// nouvelle recette
	if (!$id || !$T['recette'])
	{
		$T['recette'] = array('id' => '', 'titre' => '', 'dir' => 0, 'parent' => 0, 'note' => 0, 'duree' => 0, 'personnes' => 1, 'description' => '', 'recette' => '');
		$T['ingredients'] = array();
	}
}

/////////////////////////
// CHARGEMENT RECETTE
elseif (empty($_POST))
{
	$id = (!empty($_GET['id'])) ? (int) $_GET['id'] : 0;
	// edition
	if ($id)
	{
		$T['recette'] = MySQL::getRow('SELECT * FROM '.DB_PREF.'recettes WHERE id = :1', $id);
		
		// si existe bien, ingreds
		if ($T['recette'])
		{
			$T['ingredients'] = MySQL::query('SELECT id, id_ingredient, mesure, unite FROM '.DB_PREF.'details WHERE id_recette = :1', $id);
		}
	}
	
	// nouvelle recette
	if (!$id || !$T['recette'])
	{
		$T['recette'] = array('id' => 0, 'titre' => '', 'dir' => 0, 'parent' => 0, 'note' => 0, 'duree' => 0, 'personnes' => 1, 'description' => '', 'recette' => '');
		$T['ingredients'] = array();
	}
}

//////////////////////////
// ENREGSITREMENT RECETTE
else
{
	$id = (!empty($_POST['id'])) ? (int) $_POST['id'] : 0;
	
	// donnees
	$T['recette'] = array(
		'titre' 		=> $_POST['titre'],
		'dir' 			=> (!empty($_POST['dir']) ? 1 : 0),
		'parent' 		=> (int) (!empty($_POST['dir']) ? 0 : $_POST['parent']),
		'note' 			=> (int) $_POST['note'],
		'duree' 		=> (int) $_POST['duree'],
		'personnes' 	=> (int) $_POST['personnes'],
		'description'	=> $_POST['description'],
		'recette' 		=> $_POST['recette']
	);
	
	// mise à jour
	if ($id)
	{
		MySQL::updateRow(DB_PREF.'recettes', array('id' => $id), $T['recette']);
	}
	
	// creation
	if (!$id || !$T['recette'])
	{
		MySQL::insertRow(DB_PREF.'recettes', $T['recette']);
		$id = MySQL::insertId();
	}
	
	// ajout de l'id dans les donnees
	$T['recette']['id'] = $id;
	
	// ingredients
	foreach($_POST['ingredients'] as $i)
	{
		$i['id'] = (int) $i['id'];
		$i['mesure'] = (float) $i['mesure'];
		$i['id_ingredient'] = (int) $i['id_ingredient'];
		
		// si ingredient valide
		if (!empty($i['mesure']) && !empty($i['unite']) && (!empty($i['id_ingredient']) || !empty($i['new_ingred'])))
		{
			// del
			if (!empty($i['del']))
			{
				if (!empty($i['id']))
				{
					MySQL::query('DELETE FROM '.DB_PREF.'details WHERE id = :1', $i['id']);
				}
			}
			else
			{
				// nouvel ingredient
				if (!empty($i['new_ingred']))
				{
					// si pas deja existant
					if (!($i['id_ingredient'] = array_search($i['new_ingred'], $T['all_ingredients'])))
					{
						MySQL::insertRow(DB_PREF.'ingredients', array('nom' => $i['new_ingred']));
						$i['id_ingredient'] = (int) MySQL::insertId();
						$T['all_ingredients'][$i['id_ingredient']] = $i['new_ingred'];
					}
				}
				
				// update detail
				if(!empty($i['id']))
				{
					MySQL::updateRow(DB_PREF.'details', array('id' => $i['id']), array('mesure' => $i['mesure'], 'unite' => $i['unite'], 'id_ingredient' => $i['id_ingredient']));
				}
				
				// ajout detail
				else
				{
					MySQL::insertRow(DB_PREF.'details', array('mesure' => $i['mesure'], 'unite' => $i['unite'], 'id_ingredient' => $i['id_ingredient'], 'id_recette' => $id));
				}
			}
		}
	}
	
	// recup ingredients
	$T['ingredients'] = MySQL::query('SELECT id, id_ingredient, mesure, unite FROM '.DB_PREF.'details WHERE id_recette = :1', $id);
	
	// image par defaut si nécéssaire
	if (!is_file('images/'.$id.'.jpg'))
	{
		copy('images/0.jpg', 'images/'.$id.'.jpg');
		copy('images/0_large.jpg', 'images/'.$id.'_large.jpg');
	}
}

// recup parents
$T['parents'] = array();
$parents = MySQL::query('SELECT id, titre FROM '.DB_PREF.'recettes WHERE dir = 1');
foreach($parents as $p)
	$T['parents'][$p['id']] = $p['titre'];