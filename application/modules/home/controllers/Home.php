<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el manejo de datos de la pagina principal
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Home extends MX_Controller {

	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Devuelve el resultado de la busqueda por ciudad, genero musical y artista.
	 */
	public function search() {
		var_dump($this->input->post());
	}
}