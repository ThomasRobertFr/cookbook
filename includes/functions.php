<?php

function array_map_recursive()
{
	$args = func_get_args();
	$callback = array_shift($args);
	$fn = __FUNCTION__;
	
	$out = array();
	$max = count(max($args));
	for($i=0; $i<$max; $i++) {
		if(count($args)==1) {
			foreach($args[0] as $key=>$value) {
				if(is_array($value))
					$out[$key] = $fn($callback, $value);
				else
					$out[$key] = call_user_func($callback, $value);
			}
		} else {
			$is_array = false;
			$callbacks_args = array();
			foreach($args as $array) {
				$values = array_values($array);
				if(isset($values[$i]))
					$value = $values[$i];
				else
					$value = '';
				
				if(is_array($value)) {
					$is_array = true;
					$callbacks_args[] = $value;
				} else {
					$callbacks_args[] = $value;
				}
			}
			
			if($is_array) {
				$m = count(max($callbacks_args));
				$new_callback_args = array($callback);
				foreach($callbacks_args as $arg) {
					if(!is_array($arg))
						$new_callback_args[] = array_fill(0, $m, $arg);
					else
						$new_callback_args[] = $arg;
				}
				$out[] = call_user_func_array($fn, $new_callback_args);
			} else {
				$out[] = call_user_func_array($callback, $callbacks_args);
			}
		}
	}
	
	return $out;
}

// désactive les magic quotes si elles sont activées sur le serveur.
function magic_quotes_init()
{
	if(get_magic_quotes_gpc())
	{
	   $_GET = array_map_recursive('stripslashes', array_map_recursive('trim', $_GET));
	   $_POST = array_map_recursive('stripslashes', array_map_recursive('trim', $_POST));
	   $_COOKIE = array_map_recursive('stripslashes', array_map_recursive('trim', $_COOKIE));
	}
	else
	{
	   $_GET = array_map_recursive('trim', $_GET);
	   $_POST = array_map_recursive('trim', $_POST);
	   $_COOKIE = array_map_recursive('trim', $_COOKIE);
	}
}

// recuperer le nom du fichier a inclure
function get_include(&$T)
{
	global $PAGES;
	if (isset($_GET['p']) && isset($PAGES[$_GET['p']]))
	{
		$T['titre'] = $PAGES[$_GET['p']];
		return $_GET['p'];
	}
	else
	{
		$T['titre'] = 'Liste des recettes';
		return 'list';
	}
}

// recuperer tous les ingredients
function get_ingredients()
{
	$return = array();
	$sql = MySQL::query('SELECT * FROM '.DB_PREF.'ingredients ORDER BY nom ASC');
	foreach($sql as $i)
	{
		$return[$i['id']] = $i['nom'];
	}
	
	return $return;
}

// aficher une mesure proprement
function get_mesure($mesure, $unite, $ingredient)
{
	global $UNITES_SHOW;
	
	$out = $mesure.' ';
	
	if ($unite != 'piece')
	{
		$out .= $UNITES_SHOW[$unite];
		$out .= (in_array(strtolower($ingredient[0]), array('a', 'e', 'i', 'o', 'u', 'y')) || in_array(strtolower($ingredient[0].$ingredient[1]), array('ha', 'he', 'hi', 'ho', 'hu', 'hy'))) ? ' d\'' : ' de ';
	}
	
	$ingredient = ($mesure <= 1) ? str_replace('(s)', '', $ingredient) : str_replace('(s)', 's', $ingredient);
	
	return $out.$ingredient;
}

// afficher un temps en h min a partir de minutes
function get_time($min)
{
	if ($min % 60 == 0)
	{
		return $min / 60 .'h';
	}
	elseif ($min < 60)
	{
		return $min.' min';
	}
	else
	{
		return (($min-($min%60))/60).'h'.($min%60);
	}
}

// convertir une mesure dans l'unité principale
function convertir_mesure(&$mesure, &$unite)
{
	global $UNITES_PRINCIPALES;
	global $UNITES_RATIO;
	
	$mesure = $mesure * $UNITES_RATIO[$unite]['ratio'];
	$unite = $UNITES_PRINCIPALES[$UNITES_RATIO[$unite]['groupe']];
}

// convertir une mesure dans l'unité principale ou secondaire (la plus adaptée)
function convertir_mesure_adapte(&$mesure, &$unite, $seuil = 0.8)
{
	global $UNITES_PRINCIPALES;
	global $UNITES_SECONDAIRES;
	global $UNITES_RATIO;
	
	// groupe
	$groupe = $UNITES_RATIO[$unite]['groupe'];
	
	// retour a l'unité principale si nécéssaire
	if ($unite != $UNITES_PRINCIPALES[$groupe])
	{
		convertir_mesure($mesure, $unite);
	}
	
	// conversion dans l'unité secondaire si nécéssaire
	if ($mesure <= $seuil)
	{
		$unite = $UNITES_SECONDAIRES[$groupe];
		$mesure = $mesure / $UNITES_RATIO[$unite]['ratio'];
	}
}

// convertir pour un certain nombre de personnes
function convertir_personnes(&$mesure, $old_pers, $new_pers)
{
	$mesure = $mesure * $new_pers / $old_pers;
}

// gestion du panier
function init_panier()
{
	return (!empty($_COOKIE['panier']) && @unserialize($_COOKIE['panier'])) ? unserialize($_COOKIE['panier']) : array();
}

function save_panier($panier)
{
	setcookie('panier', serialize($panier), time()+2678400);
}

function implode_panier($panier)
{
	$str = '';
	foreach($panier as $r => $p)
	{
		$str .= ','.$r;
	}
	return substr($str, 1);
}

// affiche le texte au singulier ou au pluriel
function plur($nbr, $sing, $plur, $show_nb = true, $show_zero = true)
{
	if (!$show_zero && $nbr == 0)
		return '';
	elseif ($nbr <= 1)
		return ($show_nb) ? $nbr.' '.$sing : $sing;
	else
		return ($show_nb) ? $nbr.' '.$plur : $plur;
}

// générer un <select name="$name"> a partir d'un array
// si $first_empty on ajoute un <option> vide au début
function gen_select($array, $name, $first_empty = false, $selected = false, $disabled = false)
{
	// on initialise la sortie
	$html = '<select id="i_'.$name.'" name="'.$name.'"'. (($disabled) ? ' disabled="disabled"' : '') .'>';
	
	// si on doit ajouter un vide
	if($first_empty)
		$html .= '<option value=""></option>';
	
	// on rempli
	foreach ($array as $key => $val)
	{
		$select = ($selected == $key) ? 'selected="selected" ' : '';
		// si pas de valeur, c'est un espace, sinon c'est un genre
		if (empty($val))
			$html .= '<option disabled="disabled"></option>';
		else
			$html .= '<option '.$select.'value="'.$key.'">'.$val.'</option>';
	}
	
	// on termine avant re renvoyer
	$html .= '</select>';
	
	return $html;
}

// affiche une date propre a partir d'un timestamp. Si $force_complete, on affiche la date complète, sinon en différé (il y a ...)
function my_date($time, $force_complete = false)
{
	$diff = time() - $time;
	if($diff < 0) return false;
	
	$sec = $diff % 60;
	$min = ($diff-$sec) / 60 % 60;
	$heure = ($diff - $sec - $min * 60) / 3600 % 24;
	
	$minuit = mktime('0','0','0',date('m'),date('d'),date('Y'));
	$hier = mktime('0','0','0',date('m'),date('d')-1,date('Y'));
	
	if ($force_complete)	return 'Le '.date('d/m/Y \à H:i:s', $time);
	
	if($diff < 60)			return 'Il y a '.$diff.'s';
	elseif($diff < 3600)	return 'Il y a '.$min.' min';
	elseif($diff < 7200)	return 'Il y a '.$heure.'h'.$min;
	elseif($time > $minuit)	return 'Aujourd\'hui à '.date('H:i', $time);
	elseif($time > $hier)	return 'Hier à '.date('H:i', $time);
	else					return 'Le '.date('d/m/Y \à H:i:s', $time);
}

// transforme un texte pour etre inclus dans une URLs
function txt2url($str, $petit = true, $separator = '-')
{
	if ($petit)
		$str = mb_strtolower($str);
	
	$str = str_replace(array('&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;'),array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','u','y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y'),$str);
	
	$str = str_replace(
	array('ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ',  'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'œ',  'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ý', 'þ', 'ÿ', 'ŕ'),
	array('b', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'd', 'n', 'o', 'o', 'o', 'o', 'o', 'oe', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'b', 'y', 'r'),
	$str);
	
	$str = preg_replace('`[^A-Za-z0-9'.$separator.']+`', $separator, $str);
	$str = preg_replace('`'.$separator.'{2,}`', $separator, $str);

	$str = trim($str, $separator);
	
	return $str;
}

// recupérer une liste de pages
function liste_pages($num_page, $nbr_pages, $url_before_num, $url_after_num = '', $nbr_a_afficher = 4, $show_prev_next = true)
{
	$output = '';
	
	// pas de pages : return
	if ($nbr_pages <= 1) return '<strong>1</strong>';
	
	// debut et fin d'affichage
	$page_start = $num_page - $nbr_a_afficher;
	$page_end   = $num_page + $nbr_a_afficher;
	$before_num = $num_page - 1;
	
	// limitations du début de de la fin
	if ($page_start < 1) $page_start = 1;
	if ($page_end > $nbr_pages) $page_end = $nbr_pages;
	
	// [< Page précédente]
	if ($num_page != 1 && $show_prev_next)
	{
		$output .= '<a href="'.$url_before_num.$before_num.$url_after_num.'">&lt; Page précédente</a> ';
	}
	
	// afficher "[1]" si on ne part de 2
	if ($page_start == 2)
	{
		$before_num = $num_page - 1;
		$output .= '<a href="'.$url_before_num.'1'.$url_after_num.'">1</a> ';
	}
	
	// afficher "[1] [...]" si on ne part pas de 1 ni 2
	elseif ($page_start != 1)
	{
		$before_num = $num_page - 1;
		$output .= '<a href="'.$url_before_num.'1'.$url_after_num.'">1</a> <span class="num_page">...</span> ';
	}
	
	// afficher les pages
	for ($i=$page_start; $i <= $page_end; $i++)
	{
		if ($i != $num_page)
			$output .= '<a href="'.$url_before_num.$i.$url_after_num.'">'.$i.'</a> ';
		else
			$output .= '<strong>'.$i.'</strong> ';
	}
	
	// afficher " [Fin]" si on fini a l'avant dernier
	if ($page_end == $nbr_pages - 1)
	{
		$after_num = $num_page + 1;
		$output .= '<a href="'.$url_before_num.$nbr_pages.$url_after_num.'">'.$nbr_pages.'</a> ';
	}
	
	// afficher "[...] [Fin]" si on ne fini pas a la fin
	elseif ($page_end != $nbr_pages)
	{
		$after_num = $num_page + 1;
		$output .= '<span class="num_page">...</span> <a href="'.$url_before_num.$nbr_pages.$url_after_num.'">'.$nbr_pages.'</a> ';
	}
	
	// [Page suivante >]
	if ($num_page != $nbr_pages && $show_prev_next)
	{
		$output .= '<a href="'.$url_before_num.($num_page + 1).$url_after_num.'">Page suivante &gt;</a> ';
	}
	
	return $output;
}




function ConvertBMP2GD($src, $dest = false) {
 if(!($src_f = fopen($src, "rb"))) {
  return false;
 }
 if(!($dest_f = fopen($dest, "wb"))) {
  return false;
 }
 $header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f,
14));
 $info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant",
fread($src_f, 40));
 
 extract($info);
 extract($header);

 if($type != 0x4D42) { // signature "BM"
  return false;
 }

 $palette_size = $offset - 54;
 $ncolor = $palette_size / 4;
 $gd_header = "";
 // true-color vs. palette
 $gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
 $gd_header .= pack("n2", $width, $height);
 $gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
 if($palette_size) {
  $gd_header .= pack("n", $ncolor);
 }
 // no transparency
 $gd_header .= "\xFF\xFF\xFF\xFF";

 fwrite($dest_f, $gd_header);

 if($palette_size) {
  $palette = fread($src_f, $palette_size);
  $gd_palette = "";
  $j = 0;
  while($j < $palette_size) {
   $b = $palette{$j++};
   $g = $palette{$j++};
   $r = $palette{$j++};
   $a = $palette{$j++};
   $gd_palette .= "$r$g$b$a";
  }
  $gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
  fwrite($dest_f, $gd_palette);
 }

 $scan_line_size = (($bits * $width) + 7) >> 3;
 $scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size &
0x03) : 0;

 for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
  // BMP stores scan lines starting from bottom
  fseek($src_f, $offset + (($scan_line_size + $scan_line_align) *
$l));
  $scan_line = fread($src_f, $scan_line_size);
  if($bits == 24) {
   $gd_scan_line = "";
   $j = 0;
   while($j < $scan_line_size) {
    $b = $scan_line{$j++};
    $g = $scan_line{$j++};
    $r = $scan_line{$j++};
    $gd_scan_line .= "\x00$r$g$b";
   }
  }
  else if($bits == 8) {
   $gd_scan_line = $scan_line;
  }
  else if($bits == 4) {
   $gd_scan_line = "";
   $j = 0;
   while($j < $scan_line_size) {
    $byte = ord($scan_line{$j++});
    $p1 = chr($byte >> 4);
    $p2 = chr($byte & 0x0F);
    $gd_scan_line .= "$p1$p2";
   } $gd_scan_line = substr($gd_scan_line, 0, $width);
  }
  else if($bits == 1) {
   $gd_scan_line = "";
   $j = 0;
   while($j < $scan_line_size) {
    $byte = ord($scan_line{$j++});
    $p1 = chr((int) (($byte & 0x80) != 0));
    $p2 = chr((int) (($byte & 0x40) != 0));
    $p3 = chr((int) (($byte & 0x20) != 0));
    $p4 = chr((int) (($byte & 0x10) != 0));
    $p5 = chr((int) (($byte & 0x08) != 0));
    $p6 = chr((int) (($byte & 0x04) != 0));
    $p7 = chr((int) (($byte & 0x02) != 0));
    $p8 = chr((int) (($byte & 0x01) != 0));
    $gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
   } $gd_scan_line = substr($gd_scan_line, 0, $width);
  }
    
  fwrite($dest_f, $gd_scan_line);
 }
 fclose($src_f);
 fclose($dest_f);
 return true;
}

function imagecreatefrombmp($filename) {
 $tmp_name = tempnam("/tmp", "GD");
 if(ConvertBMP2GD($filename, $tmp_name)) {
  $img = imagecreatefromgd($tmp_name);
  unlink($tmp_name);
  return $img;
 } return false;
}