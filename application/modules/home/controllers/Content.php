<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para mostrar el contenido de la pagina principal
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 29/07/2016
 */
class Content extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('parser');
		$this->load->helper('url');
		$this->load->model('Data_model');
	}

	/**
	 * Muestra el contenido de la pagina principal.
	 */
	public function pagina_principal() {
		$customData['barrios'] = $this->Data_model->list_barrios();
		$customData['generos'] = $this->Data_model->list_generos();
		return $this->parser->parse('content.html', $customData, true);
	}
}