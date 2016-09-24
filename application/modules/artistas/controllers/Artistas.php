<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('TAMANO_MAXIMO') or define('TAMANO_MAXIMO', 2000001);

class Artistas extends MX_Controller {

	function __construct() {
		parent::__construct();
		modules::run('user/authorize/authorize');
		$this->load->model('Artistas_model');
	}

	public function index($slug){
		// echo 'hola';
	}

	/**
	 * Recibe los datos para registrar un nuevo artista y los guarda en la base.
	 */
	public function nuevo_artista() {
		$data = $this->input->post();
		$this->load->library('form_validation');
		$this->load->config('artistas/datos_generales', true);
		$this->form_validation->set_rules($this->config->item('datos_generales'));

		if (!$this->form_validation->run() || count($data['integrantes']) != count($data['rol'])){
			redirect('artistas/nuevo');
		}
		$this->load->library('session');
		$id_usuario = $this->session->id_usuario;
		$id_artista = $this->Artistas_model->guardar_datos_generales($data, $id_usuario);

		redirect("artistas/descripcion/$id_artista");
	}

	/**
	 * Recibe los datos de la descripción del artista, sube las fotos a AWS y los guarda en la base.
	 */
	public function descripcion_artista() {
		// validacion form
		if(strlen($_POST['descripcion'])>2500)
			die(0);

		$this->load->model('Artistas_model');
		$this->load->library('session');

		// autorizacion 
		if(!isset($_POST['id_artista']))
			die('No estás autorizado');
		if(!$this->Artistas_model->autorizar_usuario($this->session->id_usuario, $_POST['id_artista']))
			die('No estás autorizado');
		if(empty($_POST['descripcion']))
			die(0);

		// validacion upload files
		if(isset($_FILES['file'])){
			$cant = count($_FILES['file']['name']);
			if($cant>6)
				die(0);
			for ($i = 0; $i < $cant; $i++){
				if(substr($_FILES['file']['type'][$i], 0, 5) != 'image')
					die(0);
				if($_FILES['file']['size'][$i] > TAMANO_MAXIMO)
					die(0);

				$key = 'fotos/artistas/'.$_POST['id_artista'].'-'.$i.'.'.pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
				$file_batch[] = [
					'key' => $key,
					'sourcefile' => $_FILES['file']['tmp_name'][$i],
					'contenttype' => $_FILES['file']['type'][$i]
				];

				$url[] = [
					'id_artista' => $_POST['id_artista'],
					'url' => 'http://s3-us-west-2.amazonaws.com/musicapp-001/'.$key
				];
			}

			$this->load->library('aws/aws');
			$this->aws->upload_batch($file_batch);
			$this->Artistas_model->insertar_url_fotos($url);
		}

		// fotos facebook

		// guardar urls y descripcion en la base
		$this->Artistas_model->actualizar_artista($_POST['id_artista'], ['descripcion' => $_POST['descripcion'], 'estado' => 1]);
		echo 1;
	}
	
	/**
	 * Recibe los datos de los links del artista y los guarda en la base.
	 */
	public function links_artista() {
		$this->load->model('Artistas_model');
		$this->load->library('session');

		if(!isset($_POST['id_artista']))
			die('No estás autorizado');
		if(!$this->Artistas_model->autorizar_usuario($this->session->id_usuario, $_POST['id_artista']))
			die('No estás autorizado');

		$data = $this->input->post();

		if(isset($data['facebook']))
			$data_actualizar['facebook'] = $data['facebook'];
		if(isset($data['instagram']))
			$data_actualizar['instagram'] = $data['instagram'];
		if(isset($data['twitter']))
			$data_actualizar['twitter'] = $data['twitter'];
		if(isset($data['web']))
			$data_actualizar['web'] = $data['web'];
		if(isset($data['youtube']))
			$data_actualizar['youtube'] = $data['youtube'];
		if(isset($data['publicar']))
			$data_actualizar['publicar'] = true;

		$data_actualizar['estado'] = 2;

		$this->Artistas_model->actualizar_artista($_POST['id_artista'], $data_actualizar);
		redirect('home');
	}
}