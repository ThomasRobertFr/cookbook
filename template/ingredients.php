<div id="header_img" style="background: #AAA url(style/ingredients.jpg) no-repeat center center; background-size: 100%;">
	<a href="./" id="logo"></a>
	<a href="panier.html" id="panier"><span><?php echo sizeof($PANIER) ?></span></a>
	<a href="ingredients.html" id="ingredients"></a>
	<a href="aide.html" id="aide"></a>
	
	<h1>Ingrédients</h1>
</div>

<div id="content" class="padding">
	<ul id="ingred-liste">
	<?php foreach($T['ingredients'] as $k => $i) { ?>
		<li id="ingred-<?php echo $k ?>"><?php echo $i ?> <a href="#" onclick="del(<?php echo $k ?>)"><img src="style/ko25.png" height="12" /></a></li>
	<?php } ?>
	</ul>

	<input type="text" id="i_nom" value="" size ="" /> <a href="#" onclick="add($('#i_nom').val())"><img src="style/add25.png" height="12" /></a>
</div>