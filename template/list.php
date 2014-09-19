<div id="header_img" style="background: #AAA url(style/recettes<?php echo rand(1,4) ?>.jpg) no-repeat center center; background-size: 100%;">
	<a href="./" id="logo"></a>
	<a href="panier.html" id="panier"><span><?php echo sizeof($PANIER) ?></span></a>
	<a href="ingredients.html" id="ingredients"></a>
	<a href="aide.html" id="aide"></a>
	
	<?php if (isset($T['panier'])) { ?>
		<div class="boutons"><a href="editer-panier.html?clean" title="Vider le panier"><img src="style/trash35.png" alt="Vider le panier" /></a></div>
		<h1>Recettes dans le panier</h1>
	<?php } else { ?>
		<div class="boutons"><a href="editer.html"><img src="style/add35.png" alt="+" /></a></div>
		<h1>Liste des recettes</h1>
	<?php } ?>
</div>

<div id="content">

<?php /* INGREDIENTS PANIER */ if (isset($T['panier'])) { ?>
<div id="panier-ingredients">
	<h1>Ingrédients</h1>
	<ul>
	<?php foreach($T['ingredients'] as $ingred => $unites) { foreach($unites as $unite => $mesure) { ?>
		<li><?php convertir_mesure_adapte($mesure, $unite); echo get_mesure($mesure, $unite, $T['all_ingredients'][$ingred]); ?></li>
	<?php } } ?>
	</ul>
</div>

<div id="panier-recettes">
	<?php }
	
	/* LISTE RECETTES */
	
	function show_recette($r)
	{
		global $PANIER;
		?><a id="recette-<?php echo $r['id'] ?>" class="recette-el" href="recette-<?php echo $r['id'].'-'.txt2url($r['titre']) ?>.html" style="background: url(images/<?php echo $r['id'] ?>.jpg) no-repeat center center; background-size: 100%;">
			<div class="infos">
				<div class="details fltr"><?php echo '<img src="style/time11.png" alt="" /> '.get_time($r['duree']).' &nbsp; <img src="style/pers11.png" alt="Pour " /> '.$r['personnes'] ?></div>
				<div class="stars"><div class="stars_full" style="width: <?php echo $r['note'] / 2 * 12 ?>px"></div></div>
				<div class="titre"><?php echo $r['titre'] ?></div>
			</div>
			<div class="cart"><?php if(isset($PANIER[$r['id']])) { for($i = 1; $i <= $PANIER[$r['id']]; $i++) { ?><img src="style/ok25.png" alt="ok"  onclick="return rem(<?php echo $r['id']; ?>,this)" /><?php }} ?>
				<img class="cart-add" src="style/add25.png" alt="+" onclick="return add(<?php echo $r['id']; ?>)" />
			</div>
			<img src="style/transp.png" class="transp" />
		</a><?php
	}
	
	foreach($T['recettes'] as $r) {
		// recette simple
		if(!isset($r['dir']) || !$r	['dir'])
			show_recette($r);
		// dossier (non vide)
		else { ?>
		
		<div class="clearmob"></div>
		
		<div class="recettes-group" id="recette-gp-<?php echo $r['id'] ?>">
			<a id="recette-<?php echo $r['id'] ?>" class="recette-el recette-dir" href="#" onclick="return toogle_gp(<?php echo $r['id'] ?>)" style="background: url(images/<?php echo $r['id'] ?>.jpg) no-repeat center center; background-size: 100%;">
				<div class="mask"></div>
				<div class="infos">
					<div class="titre"><?php echo $r['titre'] ?></div>
				</div>
				<div class="expand"></div>
				<div class="cart"><img src="style/copy25.png" alt="a" height="18" onclick="window.location.href='editer.html?copy=<?php echo $r['id'] ?>'" /> <img src="style/edit25.png" alt="e" height="18" onclick="window.location.href='editer-<?php echo $r['id'] ?>.html'" /></div>
			</a>
			
			<?php if (isset($r['recettes'])) foreach($r['recettes'] as $rd) show_recette($rd); ?>
			<div class="clearmob"></div>
		</div>
	<?php } } ?>
	<div class="clear"></div>

<?php if (isset($T['panier'])) { ?>
</div>
<?php } ?>
</div>