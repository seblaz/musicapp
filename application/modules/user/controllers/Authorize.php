<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el manejo de la autorizacion de los usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Authorize extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
	}

	/**
	 * Chequea que el usuario este logeado y en caso de que no sea asi lo redirecciona a la pagina de login.
	 */
	public function authorize() {
		if(!$this->session->loggedin){
			$this->session->redirect = site_url() . uri_string();
			redirect('login');
		}
	}

	/**
	 * Devuelve un booleano correspondiente al estado del logueo.
	 */
	public function isloggedin(){
		if($this->session->loggedin){
			return true;
		}
		return false;
	}
}
