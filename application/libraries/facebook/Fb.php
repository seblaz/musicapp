<?php
/**
 * Clase para la manipulacion de datos con el web service de Facebook
 */
class Fb{

	/**
	 * Instancia la clase facebook
	 */
	function __construct() {
		require "autoload.php";
		
		$this->ci = &get_instance();
		$this->ci->load->config('facebook');
		
		$this->fb = new Facebook\Facebook([
			'app_id' => $this->ci->config->item('app_id'),
			'app_secret' => $this->ci->config->item('app_secret'),
			'default_graph_version' => $this->ci->config->item('default_graph_version'),
		]);
	}

	/**
	 * Devuelve un link que lleva al logueo de facebook y autorizacion de la aplicacion.
	 */
	function get_login_url(){
		$this->ci->load->helper('url');
		$helper = $this->fb->getRedirectLoginHelper();
		return $helper->getLoginUrl(site_url().'/user/login/login_facebook', ['email', 'user_photos']);
	}

	/**
	 * Devuelve un token de 60 dias de validez.
	 */
	function get_token(){
		$helper = $this->fb->getRedirectLoginHelper();
		
		try {
			$accessToken = $helper->getAccessToken();
		} catch(Exception $e) {
			exit("Error: ".$e->getMessage());
			// show_error('La no se ha podido realizar en el tiempo determinado tardado demasiado', 408);
		}

		if(empty($accessToken)){ return;}
		
		if (! $accessToken->isLongLived()) { // Exchanges a short-lived access token for a long-lived one
			try {
				$oAuth2Client = $this->fb->fb->getOAuth2Client();	// The OAuth 2.0 client handler helps us manage access tokens
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			} catch(Exception $e) {
				exit("Error: ".$e->getMessage());
				// show_error('La no se ha podido realizar en el tiempo determinado tardado demasiado', 408);
			}
		}
		return $accessToken->getValue();
	}

	/**
	 * Recibe el token (string) y devuelve los datos de registracion (array).
	 */
	function get_registration_data($token){
		try {
			$response = $this->fb->get('/me?fields=name,first_name,last_name,email,age_range', $token);
		} catch(Exception $e) {
			exit("Error: ".$e->getMessage());
			// show_error('La no se ha podido realizar en el tiempo determinado tardado demasiado', 408);
		}
		return $response->getDecodedBody();		
	}

	/**
	 * Recibe el token y un string con los campos a solicitar(separados por comas), realiza el request y
	 * devuelve los datos en un array asociativo.
	 */
	function get_data($token, $fields = ''){
		try {
			$response = $this->fb->get('/me?fields='.$fields, $token);
		} catch(Exception $e) {
			exit("Error: ".$e->getMessage());
			// show_error('La no se ha podido realizar en el tiempo determinado tardado demasiado', 408);
		}
		return $response->getDecodedBody();
	}

	/**
	 * Devuelve un array de strings con los permisos actuales.
	 */
	function get_current_permissions($token){
		try {
			$response = $this->fb->get('/me/permissions', $token);
		} catch(Exception $e) {
			exit("Error: ".$e->getMessage());
			// show_error('La no se ha podido realizar en el tiempo determinado tardado demasiado', 408);
		}
		return $response->getDecodedBody();
	}

	/**
	 * Devuelve un link para volver a pedir un permiso.
	 */
	function rerequest_permissions_link($token, $redirecturl, $permissions){
		$this->ci->load->helper('url');
		$helper = $this->fb->getRedirectLoginHelper();
		return $helper->getReRequestUrl(site_url().$redirecturl, $permissions);
	}

	/**
	 * Devuelve un array con el source de las fotos del usuario en facebook
	 */
	function get_user_photos($token){
		try {
			$response = $this->fb->get('/me/photos?fields=source&limit=25', $token);
		} catch(Exception $e) {
			exit("Error: ".$e->getMessage());
			// show_error('La no se ha podido realizar en el tiempo determinado tardado demasiado', 408);
		}
		return $response->getDecodedBody();
	}
}