
$(document).ready(function(){
	$("#email_link").click(function(event) {
		$.ajax({
			type: 'POST',
			url: site_url + 'user/register/reenviar_email_activacion',
			data: $(this).data()
		}).done(function(result) {
			switch (result) {
				case '1':
					alert('Tu cuenta ya se encuentra activa!');
					window.location.replace(site_url);
					break;
				case '2':
					alert('No te encuentras registrado!');
					window.location.replace(site_url);
					break;
			}
		});
	});
});