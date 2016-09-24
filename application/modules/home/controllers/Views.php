<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para mostrar las vistas de la pagina principal
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Views extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('parser');
	}

	/**
	 * Muestra la pagina principal.
	 */
	public function index() {
		$this->load->module('dashboard');
		$this->dashboard->load('home.json', true);
	}
	
	/**
	 * Devuelve el resultado de la busqueda por ciudad, genero musical y artista.
	 */
	public function search() {
		var_dump($this->input->post());
	}
}
