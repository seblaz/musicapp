<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para el manejo de de los artistas.
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 31/07/2016
 */
class Artistas_model extends CI_Model {

	// function __construct() {
	// 	parent::__construct();
	// }

	/*
	 * Guarda los datos generales de los artistas y devuelve el id de artista.
	 */
	function guardar_datos_generales($data, $id_usuario) {
		$integrantes = array_splice($data, 1, 2);
		$this->db->insert('artistas', $data);
		$insered_id =$this->db->insert_id();
		$this->db->insert('usuarios_autorizados', ['id_artista'=>$insered_id, 'id_usuario'=>$id_usuario]);
		foreach ($integrantes['integrantes'] as $k => $v) {
			$integrantes2[] = ['integrante' => $v, 'rol'=> $integrantes['rol'][$k], 'id_artista' => $insered_id];
		}
		$this->db->insert_batch('integrantes', $integrantes2);
		return $insered_id;
	}

	/**
	 * Recibe el id de artsta y el id de usuario y devuelve el resultado de la query.
	 * @string id del usuario
	 * @string id del artista
	 */
	function autorizar_usuario($id_usuario, $id_artista){
		return $this->db->get_where('usuarios_autorizados', ['id_artista'=>$id_artista, 'id_usuario'=>$id_usuario])->result();
	}

	/**
	 * Recibe el id del artista y devuelve el registro de dicho artista.
	 * @string id del artista
	 */
	function devolver_artista($id_artista){
		return $this->db->get_where('artistas', ['id'=>$id_artista])->result();
	}

	/**
	 * Actualiza los valores de un registro de la tabla artistas.
	 * @mixed id del artista
	 * @array asociativo de pares de valores campo/valor 
	 */
	function actualizar_artista($id_artista, $update){
		return $this->db->update('artistas', $update, "id=$id_artista");
	}

	/**
	 * Inserta las urls de las fotos en la base.
	 * @array con las urls de las fotos
	 */
	function insertar_url_fotos($fotos){
		$this->db->insert_batch('fotos', $fotos);
	}

	/**
	 * Devuelve un array con las urls de las fotos de un artista
	 * @string id del artista
	 */
	function get_fotos($id_artista){
		return $this->db->get_where('fotos', 'id_artista='.$id_artista)->result_array();
	}
	
	/**
	 * Devuelve un array con los integrantes del artista
	 * @string id del artista
	 */
	function get_integrantes($id_artista){
		return $this->db->get_where('integrantes', 'id_artista='.$id_artista)->result_array();
	}
}