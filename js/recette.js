function add(id, el) {
	$(el).attr("src", "style/load25.png").addClass("spinning");
	
	$.get('editer-panier.html?js&add='+id+'&pers=1', function(data) {
		if (data == 'ok')
		{
			$('#header_img .boutons').append('<a onclick="rem('+id+',this); return false;" href="editer-panier.html?rem='+id+'&pers=1"><img src="style/ok35.png" alt="ok" /></a>');
		}
		else
		{
			$('#header_img .boutons').append('<img src="style/ko35.png" alt="ko" />');
		}
		
		$(el).attr("src", "style/add35.png").removeClass("spinning");
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
			$(el).attr("src", "style/ko35.png");
		}
	});
	
	return false;
}

function change_pers()
{
	pers = prompt('Afficher pour combien de personnes ?');
	window.location.href = '?pers='+pers;
}