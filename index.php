<?php

/**
 * Cookbook
 * 
 * Copyright (C) 2014 Thomas Robert - http://www.thomas-robert.fr
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// inclusion des fichiers utiles
require 'includes/constantes.php';
require 'includes/functions.php';
require 'includes/sql.class.php';

magic_quotes_init();
$js = isset($_GET['js']);
$PANIER = init_panier();

// inclusion de la "logique" de la page
$T = array();	// array contenant tous les élements a mettre dans le template
$include = get_include($T);
if (!$js) include('pages/haut.php');
include('pages/'.$include.'.php');

// sauvegarde du panier en cookie
save_panier($PANIER);

// inclusion du rendu de la page
if ($js)
	echo (!empty($T['js_out'])) ? $T['js_out'] : '';
else
{
	include('template/haut.php');
	if (empty($T['fatal_error']))
		include('template/'.$include.'.php');
	include('template/bas.php');
}