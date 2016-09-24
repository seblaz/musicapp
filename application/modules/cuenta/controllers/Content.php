<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MX_Controller {

	function __construct() {
		parent::__construct();
	}

	public function resumen_middle_section() {
		return $this->load->view('resumen/middle_section.html', '', true);
	}
	
	public function resumen_first_section() {
		return $this->load->view('resumen/first_section.html', '', true);
	}
	
	public function artistas_middle_section() {
		return $this->load->view('artistas/middle_section.html', '', true);
	}
}
