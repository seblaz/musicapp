<?php

function get_login_data(&$ci){
	$ci->load->library('session');
	if(isset($ci->session->loggedin)){
		$customData['loggedin'] = true;		
		$customData['id_usuario'] = $ci->session->id_usuario;
		$customData['name'] = $ci->session->nombre . ' ' . $ci->session->apellido;
		if($ci->session->tipo == 1){
			$customData['id_usuario'] = $ci->session->id_facebook;
			$customData['facebook'] = true;
		}else{
			$customData['facebook'] = false;			
		}
		return $customData;
	}
	return ['loggedin'=>false];
}