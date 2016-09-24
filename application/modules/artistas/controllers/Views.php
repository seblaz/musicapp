<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para mostrar las vistas del perfil del usuario
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 28/07/2016
 */
class Views extends MX_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library('parser');
		$this->load->helper(array('form', 'url'));
	}

	/**
	 * Muestra la vista para crear un nuevo artista.
	 */
	public function nuevo(){
		modules::run('user/authorize/authorize');
		$this->load->module('dashboard');
		$this->dashboard->load('nuevo.json');
	}

	/**
	 * Muestra la vista para completar la descripcion y subir las fotos.
	 */
	public function descripcion($id_artista = 0){
		modules::run('user/authorize/authorize');
		$this->load->model('Artistas_model');
		$this->load->library('session');
		
		if($this->Artistas_model->autorizar_usuario($this->session->id_usuario, $id_artista)){
			if($this->Artistas_model->devolver_artista($id_artista)[0]->estado != 0)
				redirect('artistas/links/'.$id_artista);
			$this->load->module('dashboard');
			$this->dashboard->load('descripcion.json');
		}else{
			die('ente');
			show_404();
		}
	}

	/**
	 * Muestra la vista para completar los links a paginas externas.
	 */
	public function links($id_artista = 0){
		modules::run('user/authorize/authorize');
		$this->load->model('Artistas_model');
		$this->load->library('session');

		if($this->Artistas_model->autorizar_usuario($this->session->id_usuario, $id_artista)){
			if($this->Artistas_model->devolver_artista($id_artista)[0]->estado != 1)
				redirect('home');
			$this->load->module('dashboard');
			$this->dashboard->load('links.json');
		}else{
			show_404();
		}
	}

	/**
	 * Muestra el perfil publico del artista
	 */
	public function perfil($artista){
		$this->load->module('dashboard');
		$this->dashboard->load('perfil.json');
	}
}