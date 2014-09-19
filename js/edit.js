$(document).ready(function(){
	
	// On ajoute la propriété spéciale dataTransfer à nos events jQuery
	$.event.props.push("dataTransfer");
	
	// On pose les évènements nécessaires au drag'n'drop
	if ($('#i_id').val() != '')
	{
		$('#header_img').bind({
			"dragenter dragexit dragover" : do_nothing,
			drop : drop
		});
	}
});

function drop(evt){
	do_nothing(evt);
		
	var files = evt.dataTransfer.files;
	
	// On vérifie que des fichiers ont bien été déposés
	if(files.length>0){
		for(var i in files){
			// Si c'est bien un fichier
			if(files[i].size!=undefined) {
				
				var fic=files[i];
				
				// On ajoute un listener progress sur l'objet xhr de jQuery
				// xhr = jQuery.ajaxSettings.xhr();
				// if(xhr.upload){
					// xhr.upload.addEventListener('progress', function (e) {
						// console.log(e);
						// update_progress(e,fic);
					// },false);
				// }
				// provider=function(){ return xhr; };
				
				// On construit notre objet FormData
				var fd=new FormData;
				fd.append('fic',fic);
				
				// Requete ajax pour envoyer le fichier
				$.ajax({
					url:'editer-'+$('#i_id').val()+'.html?js',
					type: 'POST',
					data: fd,
					//xhr:provider,
					processData:false,
					contentType:false,
					complete:function(data){
						$('#upload-dnd').removeClass('ip');
						if (data.responseText != 'ok')
							$('#upload-dnd').addClass('ko');
						else
						{
							$('#header_img').css('background', '#AAA url(images/'+$('#i_id').val()+'_large.jpg?'+Math.random()+') no-repeat center center');
							$('#header_img').css('background-size', '100%');
						}
					}
				});
				
				
				// On prépare la barre de progression au démarrage
				// var id_tmp=fic.size;
				// $('#output').after('<div class="progress_bar loading" id="'+id_tmp+'"><div class="percent">0%</div></div>');
				// $('#output').addClass('output_on');
				
				// On ajoute notre fichier à la liste
				// $('#output-listing').append('<li>'+files[i].name+'</li>');
				
				// affichage du lancement
				$('#upload-dnd').removeClass('ko');
				$('#upload-dnd').addClass('ip');
				
			}
		}
	}

}

// Fonction stoppant toute évènement natif et leur propagation
function do_nothing(evt){
	evt.stopPropagation();
	evt.preventDefault();
}

// Mise à jour de la barre de progression
// function update_progress(evt,fic) {
	
	// var id_tmp=fic.size;
	
	// if (evt.lengthComputable) {
		// var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
		// if (percentLoaded <= 100) {
			// $('#'+id_tmp+' .percent').css('width', percentLoaded + '%');
			// $('#'+id_tmp+' .percent').html(percentLoaded + '%');
		// }
	// }
// }

