<div id="header_img" class="big" style="background: #AAA url(images/<?php echo $T['recette']['id'] ?>_large.jpg) no-repeat center center; background-size: 100%;">
	<a href="./" id="logo"></a>
	<a href="panier.html" id="panier"><span><?php echo sizeof($PANIER) ?></span></a>
	<a href="ingredients.html" id="ingredients"></a>
	<a href="aide.html" id="aide"></a>
	
	<h1><?php echo $T['recette']['titre']; ?></h1>
	
	<div class="infos">
		Note : <div class="stars_big"><div class="stars_big_full" style="width: <?php echo $T['recette']['note'] / 2 * 14 ?>px"></div></div>
		// <img src="style/time17.png" alt="Durée :" /> <?php echo get_time($T['recette']['duree']); ?>
		// <img src="style/pers17.png" alt="Pour :" /> <span class="click" onclick="change_pers();"><?php echo $T['recette']['personnes']; ?> pers<?php if ($T['pers']) echo ' (Affiché pour '.$T['pers'].')'; ?></span>
	</div>
	
	<?php if(!empty($T['recette']['description'])) { ?>
	<div class="desc">
		<?php echo $T['recette']['description'] ?>
	</div>
	<?php } ?>
	
	<div class="boutons">
		<a href="editer-<?php echo $T['recette']['id'] ?>.html"><img src="style/edit35.png" alt="edit" /></a>
		<a href="editer.html?copy=<?php echo $T['recette']['id'] ?>"><img src="style/copy35.png" alt="+" /></a>
		<a onclick="add(<?php echo $T['recette']['id'] ?>,this); return false;" href="editer-panier.html?add=<?php echo $T['recette']['id'] ?>&pers=<?php echo $T['recette']['personnes'] ?>"><img src="style/add35.png" alt="+" /></a><br/>
		<?php if(isset($PANIER[$T['recette']['id']])) { for($i = 1; $i <= $PANIER[$T['recette']['id']]; $i++) { ?>
			<a  onclick="rem(<?php echo $T['recette']['id'] ?>,this); return false;" href="editer-panier.html?rem=<?php echo $T['recette']['id'] ?>&pers=1"><img src="style/ok35.png" alt="ok" /></a>
		<?php }} ?>
	</div>
	
</div>

<div id="content">
	<div class="recette">
		<div class="left">
			<h2>Recette</h2>
			<?php echo $T['recette']['recette'] ?>
		</div>
		
		<div class="right">
			<h2>Ingredients</h2>
			<ul>
				<?php foreach($T['ingredients'] as $i) { ?>
					<li><?php echo get_mesure($i['mesure'], $i['unite'], $i['nom']) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>