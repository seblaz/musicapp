<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el manejo del login los usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Recuperar extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->helper('url');
	}

	/**
	 * Recibe el email por post (ajax) y envia el email de recuperacion de cuenta (en caso de que corresponda).
	 */
	public function index() {
		if(!$this->input->is_ajax_request()){ redirect('login/recuperar');} // direct access
		
		$data = $this->input->post();
		if(empty($data['email'])){ return; } // altered html

		$datos_usuario = $this->User_model->recuperar_usuario($data['email']);
		if(empty($datos_usuario)){
			echo false;
		}else{
			$this->load->module('user/emails');
			$this->emails->recuperar_cuenta($datos_usuario);
			echo true;
		}
	}
}