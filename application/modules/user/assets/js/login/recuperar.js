$.validator.addMethod('email', function (value) { 
	return /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/.test(value); 
}, 'Por favor, introduce un email válido.');

$(document).ready(function(){

	$("#recover_form").validate();
	
	$("#recover_form").validate({
		rules:{
			email:email
		}
	});
	
	$("#recover_form").submit(function(e) {
		e.preventDefault();
		if($(this).valid()){
			data = $(this).serializeArray();
			$.ajax({
				type: 'POST',
				url: site_url + 'user/recuperar',
				data: data
			}).done(function(result) {
				if(result){
					$("#message").html('Se ha enviado un link de recuperacion a su dirección de email.');
				}else{
					$("#message").html('La casilla de email proporcionada no se encuentra registrada.');
				}
			});
		}
	});
});