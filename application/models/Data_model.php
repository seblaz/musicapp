<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para el manejo de datos generales de la pagina.
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class Data_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/*
	 * Lista los barrios
	 */
	function list_barrios() {
		return $this->db->get_where('barrios', array('borrado' => false))->result();
	}

	/*
	 * Lista los generos
	 */
	function list_generos() {
		return $this->db->get_where('generos', array('borrado' => false))->result();
	}

	/**
	 * Recibe el id del genero y devuelve el string correspondiente. Si no lo encuentra devuelve null.
	 * @string id del genero.
	 */
	function get_genero($id_genero){
		return $this->db->get_where('generos', array('id' => $id_genero))->result()[0]->genero;
	}
}