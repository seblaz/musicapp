<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para cargar el contenido.
 */
class Dashboard {

	function __construct() {
		$this->ci = &get_instance();
		$this->ci->load->helper('url');
		$this->ci->load->library('parser');
	}

	function load_view($content, $header_array){
		$this->ci->parser->parse('head.html', $header_array);
		$this->ci->load->view('header.html');
		echo $content;		
		$this->ci->load->view('footer.html');
	}
}