function leapYear(year) {
	return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
}

var tabla_meses = [];
tabla_meses['01'] = 31;
tabla_meses['02'] = 28;
tabla_meses['03'] = 31;
tabla_meses['04'] = 30;
tabla_meses['05'] = 31;
tabla_meses['06'] = 30;
tabla_meses['07'] = 31;
tabla_meses['08'] = 31;
tabla_meses['09'] = 30;
tabla_meses['10'] = 31;
tabla_meses['11'] = 30;
tabla_meses['12'] = 31;

jQuery.validator.addMethod("alphanumeric", function(value, element) {
		return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
});

$.validator.addMethod('email', function (value) { 
	return /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/.test(value); 
}, 'Por favor, introduce un email válido.');

var LIMITE_EDAD = 12;

$(document).ready(function(){
	
	$("#reg_form").validate({
		// groups: {
		// 	fecha_nacimiento: "fecha_nacimiento[dia] fecha_nacimiento[mes] fecha_nacimiento[ano]"
		// },
		// errorPlacement:function(error, element) {
		// 	error.insertAfter(element);
		// },
		rules:{
			"fecha_nacimiento[dia]": {
				max: function (element) {
					if(!($("#fecha_nacimiento_mes") || $("#fecha_nacimiento_ano"))){
						return 31;
					}
					tabla_meses['02'] = ( leapYear($("#fecha_nacimiento_ano").val()) ) ? 29 : 28;
					return tabla_meses[$("#fecha_nacimiento_mes").val()];
				}
			},
			"fecha_nacimiento[ano]":{
				max: function (element) {
					var today = new Date();
					return today.getFullYear()-LIMITE_EDAD;
				}
			},
			email:{
				remote: site_url + "user/register/check_email",
				email:true
			},
			usuario:{
				remote: site_url + "user/register/check_usuario",
				alphanumeric: true,
				minlength: 6
			},
			passw:{
				minlength: 6				
			}
		},
		messages:{
			"fecha_nacimiento[dia]":{
				max: "Por favor, introduce un día válido."
			},
			"fecha_nacimiento[ano]":{
				max: "Lo sentimos, pero no cumples con la edad necesaria para registrarte.",
				maxlenght: "Por favor, introduce un año válido."
			},
			email:{
				remote: "La dirección de email se encuentra registrada."
			},
			usuario:{
				remote: "El usuario no se encuentra disponible.",
				alphanumeric: "El usuario solo puede contener dígitos y numeros.",
				minlength: "El nombre de usuario debe tener por lo menos 6 caracteres."
			},
			passw:{
				minlength: "La contraseña debe tener al menos 6 caracteres."
			}
		}
	});
});