function add(nom) {
	$.get('ingredients.html?js&add='+nom, function(data) {
		if (data != 'ko')
		{
			$('#ingred-liste').append('<li id="ingred-'+data+'">'+nom+'</li>');
		}
		else
		{
			alert('Echec de l\'ajout de "'+nom+'". Probablement déjà existant.');
		}
	});
	
	return false;
}

function del(id, force) {
	if (force || confirm('Etes-vous sûr de vouloir supprimer cet ingrédient ?'))
	{
		var forcetxt = '';
		if (force)
			var forcetxt = '&force';
		
		$.get('ingredients.html?js&del='+id+forcetxt, function(data) {
			if (data == 'used')
			{
				if (confirm('Ingrédient utilisé dans les recettes. Forcer la suppression ?'))
				{
					del(id, true);
				}
			}
			else if (data == 'ok') {
				$('#ingred-'+id).remove();
			}
			else
			{
				$('#ingred-'+id).append(' Echec : '+data);
			}
		});
	}
	
	return false;
}