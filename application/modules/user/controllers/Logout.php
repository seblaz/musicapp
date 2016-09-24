<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el manejo del logout los usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Logout extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
	}

	/**
	 * Deslogea al usuario y lo redirecciona a home
	 */
	public function index() {
		$this->load->helper('url');
		
		$this->session->unset_userdata('loggedin');
		$this->session->sess_destroy();

		if($this->input->is_ajax_request()){	// ajax access
			echo site_url('home');
		}else{									// direct access
			redirect('home');
		}
	}
}
