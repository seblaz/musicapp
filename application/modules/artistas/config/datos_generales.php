<?php

$config = array(
	array(
		'field' => 'artista',
		'rules' => 'required|trim|max_length[250]',
	),
	array(
		'field' => 'integrantes[]',
		'rules' => 'required',
	),
	array(
		'field' => 'rol[]',
		'rules' => 'required',
	),
	array(
		'field' => 'genero',
		'rules' => 'required|is_not_unique[generos.id]',
	),
	array(
		'field' => 'ano_inicio',
		'rules' => 'required|numeric|greater_than_equal_to[1900]|less_than_equal_to_current_year',
	)
);