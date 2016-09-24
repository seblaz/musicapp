<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modelo para el manejo de datos de los usuarios
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 * @date 22/07/2016
 */
class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Registra al usuario en la base
	 */
	public function registrar($data) {
		$datos_generales = array_splice($data, 0, 2);
		$datos_generales['email'] = $data['email'];
		$r1 = $this->db->insert('usuarios', $datos_generales);
		unset($data['email']);
		$data['id_usuario'] = $this->db->insert_id();
		return ($r1 && $this->db->insert('usuarios_registrados', $data));
	}

	/**
	 * Recibe la data de registracion en un array y registra al usuario.
	 */
	public function registrar_facebook($data){
		$insert_data['nombre'] = $data['first_name'];
		$insert_data['apellido'] = $data['last_name'];
		$insert_data['email'] = isset($data['email']) ? $data['email'] : null;
				
		$r1 = $this->db->insert('usuarios', $insert_data);
		$fb_data['age_min'] = $data['age_min'];
		$fb_data['age_max'] = $data['age_max'];
		$fb_data['id'] = $data['id'];
		$fb_data['id_usuario'] = $this->db->insert_id();
		
		$r2 = $this->db->insert('usuarios_facebook', $fb_data);
		return ($r1 && $r2);
	}

	/**
	 * Recibe el codigo de activacion y el usuario y activa la cuenta a la cual dicho codigo pertenece.
	 */
	public function activar_cuenta($codigo_activacion, $usuario){
		$this->db->where(array('link_activacion' => $codigo_activacion, 'usuario' => $usuario));
		$this->db->update('usuarios_registrados', array('verificado' => true));
		return $this->db->affected_rows() == 1;
	}

	/**
	 * Recibe el email y devuelve los datos correspondientes al usuario.
	 * En caso de que no exista el usuario devuelve null.
	 */
	public function recuperar_usuario($email){
		$reg = $this->db->get_where('usuarios', array('email'=>$email))->result_array();
		if(empty($reg)){ return;}
		return $reg[0];
	}

	/**
	 * Recibe un array con el usuario o email y el password y busca la cuenta en la base.
	 * Si la cuenta existe, esta activa y la contraseña es correcta devuelve un objeto con los datos del usuario.
	 * Si no existe devuelve 0.
	 * Si la contraseña es incorrecta devuelve 1.
	 * Si la cuenta no esta activa devuelve 2.
	 */
	public function buscar_usuario($data){
		$this->db->where('email', $data['usuario_email']);
		$r_usuarios = $this->db->get('usuarios')->result();
		
		$this->db->where('usuario', $data['usuario_email']);
		$r_registrados = $this->db->get('usuarios_registrados')->result();
		
		if(empty($r_usuarios) && empty($r_registrados)){return CUENTA_INEXISTENTE;}
		
		if(empty($r_usuarios)){
			$this->db->where('id', $r_registrados[0]->id_usuario);
			$r_usuarios = $this->db->get('usuarios')->result();			
		}else{
			$this->db->where('id_usuario', $r_usuarios[0]->id);
			$r_registrados = $this->db->get('usuarios_registrados')->result();			
		}
		
		if(!$r_registrados[0]->verificado){return CUENTA_INACTIVA;}
		if(!password_verify($data['passw'], $r_registrados[0]->passw)){return CONTRASENA_INCORRECTA;}

		return array_merge((array)$r_registrados[0], (array)$r_usuarios[0]);
	}

	/**
	 * Recibe un id de usuario de facebook y devuelve los datos correspondientes del usuario.
	 */
	public function buscar_usuario_facebook($id){
		$reg = $this->db->get_where('usuarios_facebook', 'id='.$id)->result();
		return $reg;
	}

	/**
	 * Recibe un id de usuario y devuelve los datos correspondientes del usuario.
	 */
	public function buscar_usuario_registrado($id){
		$reg = $this->db->get_where('usuarios', 'id='.$id)->result();
		return $reg;
	}

	/**
	 * Actualiza el usuario.
	 */
	public function actualizar_usuario($data){
		$this->db->update('usuarios', $data);
	}

	/**
	 * Actualiza el usuario de facebook.
	 */
	public function actualizar_usuario_facebook($data, $fb_row){
		$fb_data['age_min'] = $data['age_min'];
		$fb_data['age_max'] = $data['age_max'];
		$fb_data['id'] = $data['id'];

		$r1 = $this->db->update('usuarios_facebook', $fb_data, ['id' => $fb_data['id']]);
		
		$insert_data['nombre'] = $data['first_name'];
		$insert_data['apellido'] = $data['last_name'];
		$insert_data['email'] = isset($data['email']) ? $data['email'] : null;

		$r2 = $this->db->update('usuarios', $insert_data, ['id'=>$fb_row[0]->id_usuario]);
		
		return ($r1 && $r2);
	}

	/**
	 * Recibe un array con los parametros a buscar, y devuelve los datos encontrados en la tabla "usuarios".
	 */
	public function get_user_data($where){
		$reg = $this->db->get_where('usuarios', $where, 1)->result_array();
		return $reg;
	}
	
	/**
	 * Recibe un array con los parametros a buscar, y devuelve los datos encontrados en la tabla "usuarios_registrados".
	 */
	public function get_registered_user_data($where){
		$reg = $this->db->get_where('usuarios_registrados', $where, 1)->result_array();
		return $reg;
	}

	/**
	 * Devuelve la cantidad de apariciones del email recibido como parametro en la tabla usuarios
	 */
	public function check_email($email) {
		$this->db->where('email', $email);
		return $this->db->count_all_results('usuarios');
	}

	/**
	 * Devuelve la cantidad de apariciones del usuario recibido como parametro en la tabla usuarios
	 */
	public function check_usuario($usuario) {
		$this->db->where('usuario', $usuario);
		return $this->db->count_all_results('usuarios_registrados');
	}

	/**
	 * Checkea que la cuenta este registrada y su estado de activacion.
	 * Devuelve 0 si no esta activa, 1 si esta activa y 2 si no se encuentra registrado.
	 */
	public function check_activacion($email) {
		$reg = $this->db->get_where('usuarios', array('email' => $email))->result();
		$act = $this->db->get_where('usuarios_registrados', array('id_usuario'=>$reg[0]->id))->result();
		if(count($act) != 1){return 2;}
			return intval($act[0]->verificado);
	}
}