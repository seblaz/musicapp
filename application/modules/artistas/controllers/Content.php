<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Clase para mostrar los contenidos de las paginas relacionadas con los artistas.
 */
class Content extends MX_Controller {

	function __construct() {
		parent::__construct();
	}

	public function nuevo_first_section() {
		// return $this->load->view('overview/first_section.html', '', true);
	}

	public function nuevo_middle_section() {
		modules::run('user/authorize/authorize');
		$this->load->library('parser');
		$this->load->helper(['url', 'form']);
		$this->load->model('Data_model');

		$customData['generos'] = $this->Data_model->list_generos();
		
		return $this->parser->parse('nuevo.html', $customData, true);
	}
	
	public function descripcion_middle_section(){
		modules::run('user/authorize/authorize');
		$this->load->library('facebook/fb');
		$this->load->library('session');
		$this->load->helper('url');
		
		if($this->session->tipo == 1){

			$permissions = $this->fb->get_current_permissions($this->session->facebook_token);
			foreach ($permissions['data'] as $permission) {
				if($permission['permission'] == 'user_photos' && $permission['status'] == 'declined'){
					$customData['facebook_photo_permission'] = $this->fb->rerequest_permissions_link($this->session->facebook_token, '/artistas/descripcion/'.$this->uri->segment(3), ['user_photos']);
				}
			}

			if(!isset($customData['facebook_photo_permission'])){
				$fotos = $this->fb->get_user_photos($this->session->facebook_token);
				$customData['facebook_photos'] = $fotos['data'];
				$customData['next_photos'] = $fotos['paging']['cursors']['after'];
			}
		}

		$customData['id_artista'] = $this->uri->segment(3);
		return $this->parser->parse('descripcion.html', $customData, true);		
	}

	public function links_first_section() {
		// return 'first section';
	}

	public function links_middle_section(){
		modules::run('user/authorize/authorize');
		$customData['id_artista'] = $this->uri->segment(3);
		return $this->parser->parse('links.html', $customData, true);
	}

	public function perfil(){
		$this->load->model('Artistas_model');
		$id_artista = $this->uri->segment(2);
		$artista = $this->Artistas_model->devolver_artista($id_artista);

		if($artista){
			if($artista[0]->estado == 2){
				$customData['fotos'] = $this->Artistas_model->get_fotos($id_artista);
				$customData['integrantes'] = $this->Artistas_model->get_integrantes($id_artista);
				$customData = array_merge($customData, (array) $artista[0]);
				$this->load->model('Data_model');
				$customData['genero'] = $this->Data_model->get_genero($customData['genero']);
				$customData['app_name'] = APP_NAME;
				// var_dump($customData);exit;
				// unset($customData['facebook']);
				return $this->parser->parse('perfil.html', $customData, true);
			}
		}
		show_404();
	}
}
