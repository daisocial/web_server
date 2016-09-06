<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asr_Model extends CI_model {


	function __construct() {
		parent::__construct();
	}

	function enviarOrden($oracion) {
		$datos = array('orden' => $oracion,'realizado' => 'true');
		return $this->db->insert('pilaordenes', $datos);
	}	
}	