<form id="form-recette" action="editer.html" method="post">
	<div id="header_img" class="big" style="background: #AAA url(images/<?php echo $T['recette']['id'] ?>_large.jpg) no-repeat center center; background-size: 100%;">
		<a href="./" id="logo"></a>
		<a href="panier.html" id="panier"><span><?php echo sizeof($PANIER) ?></span></a>
		<a href="ingredients.html" id="ingredients"></a>
		<a href="aide.html" id="aide"></a>
		
		<h1><label for="i_dir"><img src="style/dir.png" alt="" /><input type="checkbox" name="dir" id="i_dir" <?php if($T['recette']['dir']) echo 'checked="checked"'; ?> /></label> <input id="i_titre" type="text" name="titre" value="<?php echo htmlspecialchars($T['recette']['titre']) ?>" /></h1>
		
		<div class="infos">
			Note : <?php echo gen_select($NOTES, 'note', true, $T['recette']['note']) ?>/10
			// <img src="style/time17.png" alt="Durée :" /> <input id="i_duree" type="text" name="duree" value="<?php echo htmlspecialchars($T['recette']['duree']) ?>" size="4" /> min
			// <img src="style/pers17.png" alt="Pour :" /> <?php echo gen_select($PERSONNES, 'personnes', true, $T['recette']['personnes']) ?> pers
			// <img src="style/folder.png" alt="Dossier :" /> <?php echo gen_select($T['parents'], 'parent', true, $T['recette']['parent']) ?>
		</div>
		
		<div class="desc">
			<textarea id="i_description" name="description"><?php echo htmlspecialchars($T['recette']['description']) ?></textarea>
		</div>
		
		<div class="boutons">
			<?php if($T['recette']['id']) { ?><a href="recette-<?php echo $T['recette']['id'] ?>-<?php echo txt2url($T['recette']['titre']); ?>.html"><img src="style/back35.png" alt="Back" /></a><?php } ?>
			<?php if($T['recette']['id']) { ?><a href="editer-<?php echo $T['recette']['id'] ?>.html?del" onclick="return confirm('Voulez-vous supprimer cette recette ?');"><img src="style/trash35.png" alt="Del" /></a><?php } ?>
			
			<input type="hidden" id="i_id" name="id" value="<?php echo $T['recette']['id'] ?>" />
			<input class="button" type="submit" value="" id="i_submit" />
		</div>
		
		<?php if ($T['recette']['id']) { ?><div id="upload-dnd"><img src="style/load30.png" class="spinning" /></div><?php } ?>
		
	</div>

	<div id="content">
		<div class="recette">
			<div class="left">
				<h2>Recette</h2>
				<textarea id="i_recette" name="recette"><?php echo htmlspecialchars($T['recette']['recette']) ?></textarea>
			</div>
			
			<div class="right">
				<h2>Ingredients</h2>
				<table>
					<tr>
						
						<th class="col1">Mesure</th>
						<th class="col2">Unité</th>
						<th class="col3" colspan="2">Ingrédient connu ou nouveau</th>
						<th class="col5"><img src="style/ko25.png" height="10" alt="X" /></th>
					</tr>
				<?php $k = 0; foreach($T['ingredients'] as $k => $i) { ?>
					<tr>
						<td class="col1"><input type="hidden" name="ingredients[<?php echo $k ?>][id]" value="<?php echo $i['id'] ?>" />
							<input type="text" name="ingredients[<?php echo $k ?>][mesure]" value="<?php echo $i['mesure'] ?>" /></td>
						<td class="col2"><?php echo gen_select($UNITES, 'ingredients['.$k.'][unite]', false, $i['unite']) ?></td>
						<td class="col3"><?php echo gen_select($T['all_ingredients'], 'ingredients['.$k.'][id_ingredient]', false, $i['id_ingredient']) ?></td>
						<td class="col4"><input type="text" name="ingredients[<?php echo $k ?>][new_ingred]" value="" /></td>
						<td class="col5"><input type="checkbox" name="ingredients[<?php echo $k ?>][del]" /></td>
					</tr>
					<?php }
					for($i = 1; $i <= 7; $i++) { $k++; ?>
						<tr>
						<td class="col1"><input type="hidden" name="ingredients[<?php echo $k ?>][id]" value="0" />
							<input type="text" name="ingredients[<?php echo $k ?>][mesure]" value="" /></td>
						<td class="col2"><?php echo gen_select($UNITES, 'ingredients['.$k.'][unite]', true) ?></td>
						<td class="col3"><?php echo gen_select($T['all_ingredients'], 'ingredients['.$k.'][id_ingredient]', true) ?></td>
						<td class="col4"><input type="text" name="ingredients[<?php echo $k ?>][new_ingred]" value="" /></td>
						<td class="col5"><input type="checkbox" name="ingredients[<?php echo $k ?>][del]" /></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</form>