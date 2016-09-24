
var previewTemplateVar = `
<div class="dz-preview dz-file-preview"> 
	<div class="dz-image">
		<img data-dz-thumbnail>
	</div>
	<div class="dz-details">
		<div class="dz-filename"><span data-dz-name></span></div>
		<div class="dz-size" data-dz-size></div>
		<img data-dz-thumbnail />
	</div>
	<div class="dz-error-message"><span data-dz-errormessage></span></div>
	<div class="dz-actions">
		<div class="action-container">
			<span class="glyphicon glyphicon-trash" data-dz-remove></span>
		</div>
		<div class="action-container edit">
			<span class="glyphicon glyphicon-pencil"></span>
		</div>
	</div
</div>
`;


jQuery(document).ready(function() {
	
	$("#my-awesome-dropzone").validate();

	Dropzone.options.dZUpload = { // The camelized version of the ID of the form element

		// The configuration we've talked about above
		url: "../descripcion_artista",
		autoProcessQueue: false,
		uploadMultiple: true,
		parallelUploads: 5,
		maxFiles: 5,
		maxFilesize: 2, // MB
		// addRemoveLinks: true,
		acceptedFiles: "image/*",
		dictFallbackMessage: "Tu navegador no soporta la subida de archivos drag'n'drop.",
		dictFallbackText: "Por favor, utiliza el siguiente formulario para subir tus archivos.",
		dictInvalidFileType: "Archivo no soportado.",
		dictFileTooBig: "El archivo es demasiado pesado",
		dictRemoveFile: "Borrar",
		createImageThumbnails: true,
		maxThumbnailFilesize: 10,
		thumbnailWidth: "250",
		thumbnailHeight: "250",
		previewTemplate: previewTemplateVar,

		// The setting up of the dropzone
		init: function() {
			var myDropzone = this;

			// First change the button to actually tell Dropzone to process the queue.
			$("button[type=submit]")[0].addEventListener("click", function(e) {
				// Make sure that the form isn't actually being sent.
				if($("#my-awesome-dropzone").valid()){
					e.preventDefault();
					e.stopPropagation();
					
					if (myDropzone.files.length) {
						var current_queue = [];
						$('.dz-preview').each( function(){
							current_queue.push($(this).data('file'));
						});

						myDropzone.removeAllFiles();

						for(i=0;i<current_queue.length;i++){
							myDropzone.addFile(current_queue[i]);
						}

						myDropzone.processQueue();
					}else{
						var data = $('#my-awesome-dropzone').serializeArray();
						$.ajax({
							type: 'POST',
							url: '../descripcion_artista',
							data: data
						}).done(function(result) {
							console.log(result);
							result_handler(result);
						});
					}
				}
			});

			this.on("maxfilesexceeded", function(file){
				this.removeFile(file);
				alert('Solo podés subir hasta 5 fotos!');
			});

			this.on("addedfile", function(file) {
				
				// myDropzone.removeFile(file);

				var modal_template = ""
				
				$("#dZUpload").sortable({
				items:'.dz-preview',
				cursor: 'move',
				opacity: 0.5,
				containment: "parent",
				distance: 20,
				tolerance: 'pointer',
				update: function(e, ui){
					// do what you want
					}
				});

				$('.dz-preview:last-of-type').data('file', file);

				// Edicion de la imagen con JCrop
				$(file.previewElement).find(".action-container.edit").click(function(){
					console.log($(this).parent().parent());
					$('#crop-image').attr('src', $(this).parent().parent().find("img").attr('src'));
					$("#modal_crop").modal({backdrop: 'static'});
				});
			});

			this.on("sendingmultiple", function (data, xhr, formData) {
				formData.append("descripcion", $("#descripcion").val());
				formData.append("id_artista", $("#id_artista").val())
			});
		},

		success: function(file, result) {
			result_handler(result);
		}
	}

	$("#draginfo").click(function(){
		alert('Hacé clic sobre una foto y arrastrala a la posición que quieras.');
	});

	// Edicion de la imagen con JCrop

	// $('#modal_crop').on('show.bs.modal', function(event){
	// });
	
	var jcrop;

	$('#modal_crop').on('shown.bs.modal', function(event){
		var img = $('#crop-image');
		img.Jcrop({
			boxWidth: img.width(),
			boxHeight: img.height(),
			aspectRatio: 2
		}, function(){
			jcrop = this;
		});
	});

	$('#modal_crop').on('hide.bs.modal', function(e){
		jcrop.destroy();
	});
	
	$("#confirmar-crop").click(function(){
		var img = $('#crop-image');
		console.log(img.width());
		console.log(img.height());
	});

});

function result_handler(result){
	if(result = 0){
		window.location.reload();
	}else if(result = 1){
		window.location.replace('../links/' + $("#id_artista").val());
	}
}