<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el manejo la registracion de los usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Register extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('User_model');
	}

	/**
	 * Recibe los datos para una nueva registracion por metodo post, valida los datos y registra al usuario.
	 * En caso de que los datos proporcionados sean incorrectos redirecciona al usuario al formulario de carga del formulario,
	 * con un código de error como parámetro.
	 */
	public function index() {
		$this->load->helper('url', 'user/registration');
		$this->load->helper('user/user/registration');
		$this->load->library('form_validation');
		$this->load->config('user/registration_rules', true);

		$data = $this->input->post();

		// carga las reglas del formulario
		$this->form_validation->set_rules($this->config->item('registration_rules'));

		if (!$this->form_validation->run()) {
			redirect('user/views/registration/w57dfa86sd');
		}
		if (!validate_date($data)) {
			redirect('user/views/registration/w57dfa86sd');
		}

		$data['fecha_nacimiento'] = new DateTime($data['fecha_nacimiento']['ano'] . '-' . $data['fecha_nacimiento']['mes'] . '-' . $data['fecha_nacimiento']['dia']);
		$data['fecha_nacimiento'] = $data['fecha_nacimiento']->format('Y-m-d H:i:s');

		// borra los datos de confirmacion (email, passw), encrypta la passw y agrega el link de activacion
		unset_confirmations($data);

		// inserta la data
		if (!$this->User_model->registrar($data)) {
			redirect('user/views/registration/w57dfa86sd');
		}

		// envia el email de activacion
		$this->load->module('user/emails');
		switch($this->emails->activar_cuenta($data)){
			case 2: // no registrado
				redirect('user/views/registration/w57dfa86sd');
				break;

			case 1: // cuenta activa
				redirect('cuenta_activa');
				break;
		}	// si no entra en los anteriores se mando email

		// muestra una pagina para volver a enviar los emails
		$this->parser->parse('user/activacion_pendiente.html', $data);
	}

	/**
	 * Recibe el email, el usuario y el nombre y reenvia el email de activacion de la cuenta.
	 */
	public function reenviar_email_activacion(){
		$data = $this->input->post();
		$this->load->module('user/emails');
		echo $this->emails->activar_cuenta($data);
	}

	/**
	 * Recibe el parametro email por get y consulta en la base la cantidad de registros que existen con dicho email.
	 * Devuelve un booleano dependiendo de si existe o no.
	 */
	public function check_email() {
		if ($this->User_model->check_email($this->input->get('email')) != 0) {
			echo json_encode(false);
		} else {
			echo json_encode(true);
		}
	}

	/**
	 * Recibe el parametro usuario por get y consulta en la base la cantidad de registros que existen con dicho usuario.
	 * Devuelve un booleano dependiendo de si existe o no.
	 */
	public function check_usuario() {
		if ($this->User_model->check_usuario($this->input->get('usuario')) != 0) {
			echo json_encode(false);
		} else {
			echo json_encode(true);
		}
	}

	/**
	 * Recibe el codigo de activacion y el usuario y activa la cuenta, en caso de que corresponda
	 */
	public function ActivarCuenta($codigo_activacion = '', $usuario = ''){
		if(empty($codigo_activacion) || empty($usuario)){show_404();}
		if(!$this->User_model->activar_cuenta($codigo_activacion, $usuario)){ show_404();}
		
		$data = $this->User_model->get_registered_user_data(array('link_activacion'=>$codigo_activacion, 'usuario'=>$usuario));
		if(empty($data)){ show_404();}
		
		$data2 = $this->User_model->get_user_data(array('id'=>$data[0]['id_usuario']));
		$data = array_merge($data[0], $data2[0]);

		$this->load->library('session');
		$this->session->tipo 		= 0;
		$this->session->id_usuario	= $data['id'];
		$this->session->usuario 	= $data['usuario'];
		$this->session->nombre 		= $data['nombre'];
		$this->session->apellido 	= $data['apellido'];
		$this->session->email 		= $data['email'];
		$this->session->loggedin	= true;
		
		$this->load->helper('url');
		if(empty($this->session->redirect)){
			$this->session->redirect = site_url('home');
		}
		redirect($this->session->redirect);
	}
}
