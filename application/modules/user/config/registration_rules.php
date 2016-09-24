<?php

$config = array(
	array(
		'field' => 'nombre',
		'rules' => 'required|trim|max_length[150]',
	),
	array(
		'field' => 'apellido',
		'rules' => 'required|trim|max_length[150]',
	),
	array(
		'field' => 'usuario',
		'rules' => 'required|trim|min_length[6]|max_length[200]|is_unique[usuarios_registrados.usuario]',
	),
	array(
		'field' => 'passw',
		'rules' => 'required|min_length[6]|max_length[40]',
	),
	array(
		'field' => 'passwconf',
		'rules' => 'required|matches[passw]',
	),
	array(
		'field' => 'email',
		'rules' => 'required|trim|valid_email|is_unique[usuarios.email]',
	),
	array(
		'field' => 'emailconf',
		'rules' => 'required|trim|matches[email]',
	),
	array(
		'field' => 'fecha_nacimiento[dia]',
		'rules' => 'required',
	),
	array(
		'field' => 'fecha_nacimiento[mes]',
		'rules' => 'required',
	),
	array(
		'field' => 'fecha_nacimiento[ano]',
		'rules' => 'required|greater_than[]',
	),
);