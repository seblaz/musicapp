$(document).ready(function() {
	
	$("#nuevo_form").validate();

	// append de integrantes
	$(".integrantes-icon.plus").click(function() {
		$("#integrantes-container").append(
			'<div class="new form-group">\
				<input type="text" name="integrantes[]" class="form-control" placeholder="Nombre y Apellido" data-msg-required="Por favor, completa el nombre del integrante." data-msg-maxlength="El nombre no puede superar los 250 caracteres." required maxlength="250"> \
				<input type="text" name="rol[]" class="form-control" placeholder="Roles" data-msg-required="Por favor, completa el/los roles del integrante." data-msg-maxlength="El/los roles puede superar los 250 caracteres." required maxlength="250">\
			</div>'
		);
	});

	// remove de integrantes
	$(".integrantes-icon.minus").click(function() {
		$(".new.form-group:last").detach();
	});

	$("#nuevo_form").submit(function(e) {
		var data = $(this).serializeArray();
	});
});