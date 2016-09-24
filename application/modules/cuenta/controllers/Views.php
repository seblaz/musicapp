<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para mostrar las vistas del perfil del usuario
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 28/07/2016
 */
class Views extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('parser');
		$this->load->helper(array('form', 'url'));
		$this->load->module('user/authorize');
		$this->authorize->authorize();
	}

	/**
	 * Muestra un resumen de la cuenta.
	 */
	public function resumen(){
		$this->load->module('dashboard');
		$this->dashboard->load('resumen.json');
	}

	/**
	 * Muestra los artistas asociados al usuario actual.
	 */
	public function artistas(){
		$this->load->module('dashboard');
		$this->dashboard->load('artistas.json');
	}
}