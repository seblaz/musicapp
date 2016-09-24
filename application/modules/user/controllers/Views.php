<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para mostrar las vistasde los formularios de usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Views extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('parser');
		$this->load->helper(array('form', 'url'));
	}

	/**
	 * Muestra el formulario de registracion
	 */
	public function registration($code = null) {
		$this->load->library('facebook/fb');
		$this->load->library('session');

		$customData['App_Name'] = APP_NAME;
		$customData['facebook_link'] = $this->fb->get_login_url();

		if ($code == 'w57dfa86sd') {
			$customData['error'] = true;
		} else {
			$customData['error'] = false;
		}
		$this->parser->parse('user/registration.html', $customData);
	}

	/**
	 * Chequea que el usuario este loggueado.
	 * Si esta logueado lo redirecciona y en caso contrario muestra el formulario de login.
	 */
	public function login(){
		$this->load->module('user/authorize');
		if($this->authorize->isloggedin()){ redirect('home');} // lo lleva a home si esta logueado
		
		$this->load->library('facebook/fb');
		
		$customData['App_Name'] = APP_NAME;
		$customData['facebook_link'] = $this->fb->get_login_url();
		$this->parser->parse('user/login.html', $customData);		
	}

	/**
	 * Muestra el formulario de recuperacion de contraseñas.
	 */
	public function recuperar(){
		$this->load->library('facebook/fb');
		$this->load->library('session');
		
		$customData['App_Name'] = APP_NAME;
		$customData['facebook_link'] = $this->fb->get_login_url();
		$this->parser->parse('user/recuperar.html', $customData);
	}
}
