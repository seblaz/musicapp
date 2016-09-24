<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para el envio de emails.
 */
class Mailer {

	/**
	 * Carga la config predeterminada.
	 */
	function __construct() {
		$this->ci = &get_instance();
		$this->ci->load->config('phpmailer');
		$this->ci->load->library('phpmailer/phpmailer');

		if ($this->ci->config->item('issmtp')) {$this->ci->phpmailer->IsSMTP();}
		$this->ci->phpmailer->Mailer   = $this->ci->config->item('mailer');
		$this->ci->phpmailer->SMTPAuth = $this->ci->config->item('smtpauth');
		$this->ci->phpmailer->Host     = $this->ci->config->item('host');
		$this->ci->phpmailer->setFrom($this->ci->config->item('setfrom'));
		$this->ci->phpmailer->Username = $this->ci->config->item('username');
		$this->ci->phpmailer->Password = $this->ci->config->item('password');
		$this->ci->phpmailer->CharSet  = $this->ci->config->item('charset');
	}

	/**
	 * Envia el email con la data que recibe por parametro (en forma de array).
	 * El array debe tener las claves 'subject', 'body' y 'altbody' como string.
	 * Las claves 'addadress', 'cc' y 'bcc' debes ser arrays con el nombre como 
	 * clave y la casilla de email como valor.
	 */
	public function send_email($data = array()) {

		require 'phpmailer/PHPMailerAutoload.php';

		$this->ci->phpmailer->Subject = empty($data['subject'])?'':$data['subject'];
		$this->ci->phpmailer->Body    = empty($data['body'])?'':$data['body'];
		$this->ci->phpmailer->AltBody = empty($data['altbody'])?'':$data['altbody'];
		
		if(!empty($data['addaddress'])){
			foreach ($data['addaddress'] as $nombre => $email) {
				$this->ci->phpmailer->addAddress($email, $nombre);
			}
		}
		
		if(!empty($data['addcc'])){
			foreach ($data['addcc'] as $nombre => $email) {
				$this->ci->phpmailer->AddCC($email, $nombre);
			}
		}
		
		if(!empty($data['addbcc'])){
			foreach ($data['addbcc'] as $nombre => $email) {
				$this->ci->phpmailer->AddBCC($email, $nombre);
			}
		}
		return $this->ci->phpmailer->send();
	}
}