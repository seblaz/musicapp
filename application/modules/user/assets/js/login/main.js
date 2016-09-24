jQuery.validator.addMethod("alphanumeric", function(value, element) {
		return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
});

var CUENTA_INEXISTENTE = '0';
var CONTRASENA_INCORRECTA = '1';
var CUENTA_INACTIVA = '2';

$(document).ready(function(){
	
	$("#login_form").validate({
		rules:{
			usuario_email:{
				minlength:4
			},
			passw:{
				minlength:6
			}
		}
	});
	
	$("#login_form").submit(function(e) {
		e.preventDefault();
		if($(this).valid()){
			data = $(this).serializeArray();
			$.ajax({
				type: 'POST',
				url: site_url + 'user/login',
				data: data
			}).done(function(result) {			
				switch (result) {
					case CUENTA_INEXISTENTE:
						var mensaje = "La cuenta no se encuentra registrada, registrate <a href="+site_url+"/user/views/registration>acá</a>.";
						break;
					case CONTRASENA_INCORRECTA:
						var mensaje = "La contraseña que proporcionaste es incorrecta.";
						break;
					case CUENTA_INACTIVA:
						var mensaje = "La cuenta se encuentra inactiva, sigue los pasos de 'recuperación de contraseña' para activarla.";
						break;
					default:
						window.location.replace(result);
						break;
				}
				$("#message").html(mensaje);
			});
		}
	});
});