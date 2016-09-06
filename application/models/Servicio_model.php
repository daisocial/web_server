<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_Model extends Generic_model {


	function __construct() {
		parent::__construct();
	}

	function getDispositivos($serv=null) {
		return $null;
	}

	function getAll() {
		$resultado=null;

		if(($consulta=$this->_getTabla("servicio"))!=null){
			$resultado=array();
			foreach($consulta as $item){
				$servAux=new Servicio($item->id, $item->nombre);
				array_push($resultado, $servAux);
			}
		}
		
		return $resultado;
	}	
}	