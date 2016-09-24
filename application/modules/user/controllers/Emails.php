<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el envio de emails automaticos para la gestion de usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 25/07/2016
 */
class Emails extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('mailer');
		// $this->load->library('email');
	}

	/**
	 * Envia un email automatico con el link de activacion de la cuenta.
	 * Recibe los datos que se cargaron en el formulario de registracion y chequea que los mismos existan en la base.
	 * Devuelve 2 si no esta registrado y 1 si la cuenta ya esta activa.
	 */
	public function activar_cuenta($data) {
		// chequea el estado de la cuenta
		$this->load->model('User_model');
		$this->load->helper('url');
		$codigo = $this->User_model->check_activacion($data['email']);
		
		if($codigo!=0){return $codigo;}
		
		$this->load->helper('url');
		$this->load->library('parser');

		$customData['app_name'] = APP_NAME;
		$customData['nombre'] = $data['nombre'];
		$customData['activation_link'] = site_url('user/register/ActivarCuenta').'/'.hash('sha512',$data['email']).'/'.$data['usuario'];

		$email_data['subject'] = APP_NAME.' - Activá tu cuenta';
		$email_data['body'] = $this->parser->parse('user/emails/activacion.html', $customData, true);
		$email_data['altbody'] = $email_data['body']; // sin renderizar
		$email_data['addaddress'] = array($data['nombre'] => $data['email']);

		$this->mailer->send_email($email_data);
		return 0;
	}

	/**
	 * Recive un array con los datos del usuario y envia el email de recuperacion de cuenta.
	 */
	public function recuperar_cuenta($data){
		$this->load->library('parser');
		$this->load->helper('url');

		$customData['nombre'] = $data['nombre'];		
		$customData['link_recuperacion'] = site_url();
		
		$email_data['subject'] = APP_NAME.' - Recuperá tu contraseña';
		$email_data['body'] = $this->parser->parse('user/emails/recuperar.html', $customData, true);
		$email_data['altbody'] = $email_data['body']; // sin renderizar
		$email_data['addaddress'] = array($data['nombre'] => $data['email']);
		
		return $this->mailer->send_email($email_data);
	}
}
