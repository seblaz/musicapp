<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el manejo del login los usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Login extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('session');
		$this->load->helper('url');
	}

	/**
	 * Recibe los datos de inicio de sesion por POST y busca los datos en la base.
	 * En caso de que los datos sean incorrectos devuelve un entero correspondiente al error.
	 * En caso contrario loguea al usuario.
	 */
	public function index() {
		if(!$this->input->is_ajax_request()){ redirect('login');} // direct access
		
		$datos_usuario = $this->input->post();
		if(empty($datos_usuario['usuario_email']) || empty($datos_usuario['passw'])){ return; } // altered html
		
		$datos_usuario = $this->User_model->buscar_usuario($datos_usuario);
		if(is_integer($datos_usuario)){ // user not found
			echo $datos_usuario;
		}else{ 	// user ok
			$this->session->tipo 		= 0;
			$this->session->id_usuario	= intval($datos_usuario['id']);
			$this->session->usuario 	= $datos_usuario['usuario'];
			$this->session->nombre 		= $datos_usuario['nombre'];
			$this->session->apellido 	= $datos_usuario['apellido'];
			$this->session->email 		= $datos_usuario['email'];
			$this->session->loggedin	= true;
			if(empty($this->session->redirect)){
				$this->session->redirect = site_url('home');
			}
			echo $this->session->redirect;
		}
	}

	/**
	 * Devuelve la variable $this->session->redirect a los pedidos realizados por ajax.
	 * Sino redirecciona al login.
	 */
	public function get_redirect(){
		if(!$this->input->is_ajax_request()){ redirect('login');} // direct access
		if($this->session->redirect){
			echo $this->session->redirect;
		}else{
			echo site_url('home');
		}
	}

	/**
	 * Loguea y registra al usuario con el token de facebook.
	 */
	public function login_facebook(){
		$this->load->library('facebook/fb'); //clase facebook

		$token = $this->fb->get_token();
		
		if (empty($token)) {show_404();}
		
		$this->session->facebook_token = $token;
		
		$data = $this->fb->get_registration_data($token);
		
		$data['age_min'] = $data['age_range']['min'];
		$data['age_max'] = $data['age_range']['max'];
		unset($data['age_range']);

		$fb_row = $this->User_model->buscar_usuario_facebook($data['id']);
		if(count($fb_row) == 1){ // chequea que no este regstrado
			$this->User_model->actualizar_usuario_facebook($data, $fb_row); 	//actualiza la base
		}else{
			$this->User_model->registrar_facebook($data); //registra en la base
		}
		$this->session->tipo		= 1;
		$this->session->id_usuario	= $fb_row[0]->id_usuario;
		$this->session->id_facebook	= $data['id'];
		$this->session->unset_userdata('usuario');
		$this->session->nombre 		= $data['first_name'];
		$this->session->apellido 	= $data['last_name'];
		$this->session->email 		= $data['email'];;
		$this->session->loggedin	= true;

		if(empty($this->session->redirect)){
			$this->session->redirect = site_url('home');
		}
		redirect($this->session->redirect);
	}
}