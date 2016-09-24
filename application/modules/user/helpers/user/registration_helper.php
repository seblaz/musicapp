<?php

/**
 * Valida que la fecha que se encuentra en el array $data sea valido
 */
function validate_date($data) {
	return checkdate($data['fecha_nacimiento']['mes'], $data['fecha_nacimiento']['dia'], $data['fecha_nacimiento']['ano']);
}

/**
 * Borra los valores de confirmacion del array $data y realiza un hashing con blowfish de la passw
 */
function unset_confirmations(&$data) {
	unset($data['passwconf']);
	unset($data['emailconf']);
	$data['passw'] = password_hash($data['passw'], PASSWORD_BCRYPT);
	$data['link_activacion'] = hash('sha512', $data['email']);
}
