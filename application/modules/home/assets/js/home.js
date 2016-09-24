/*globals site_url*/
$(document).ready(function($) {
	$("#submit").click(function(event) {
		$.ajax({
			url: site_url+'/home/search',
			type: 'POST',
			data: {
				ciudad: $("#ciudad").val(),
				genero: $("#genero").val(),
				artista: $("#artista").val()
			}
		})
		.done(function(result) {
			console.log(result);
			// $("body").append('<div class="cartel">Hola</div>');
		})
		.fail(function() {
			$('.alert').alert();
		})
	});
});