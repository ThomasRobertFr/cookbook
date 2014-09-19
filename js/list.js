function add(id) {
	$('#recette-'+id+' .cart-add').attr("src", "style/load25.png").addClass("spinning");
	
	$.get('editer-panier.html?js&add='+id+'&pers=1', function(data) {
		if (data == 'ok')
		{
			$('#recette-'+id+' .cart').prepend('<img src="style/ok25.png" alt="ok" onclick="rem('+id+',this); return false;" />');
		}
		else
		{
			$('#recette-'+id+' .cart').prepend('<img src="style/ko25.png" alt="ko" />');
		}
		
		$('#recette-'+id+' img.cart-add').attr("src", "style/add25.png").removeClass("spinning");
	});
	
	return false;
}

function rem(id, el) {
	$(el).attr("src", "style/load25.png").addClass("spinning");
	
	$.get('editer-panier.html?js&rem='+id+'&pers=1', function(data) {
		if (data == 'ok')
		{
			$(el).remove();
		}
		else
		{
			$(el).attr("src", "style/ko25.png");
		}
	});
	
	return false;
}

function toogle_gp(id)
{
	var gp = $('#recette-gp-'+id);
	if (gp.hasClass('opened'))
	{
		gp.removeClass('opened');
	
	}
	else
	{
		gp.addClass('opened');
	}
	return false;
}