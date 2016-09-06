<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Habitacion_Model extends Generic_model {


	function __construct() {
		parent::__construct();
	}

	function getHabitacion($nombre=null){
		$resultado=null;
		if($nombre!=null){
			$str="SELECT * FROM habitacion WHERE nombre='".$nombre."'";
			$consulta =$this->db->query($str)->result();
			if($consulta!=null && count($consulta)==1){
				$resultado=new Habitacion($consulta[0]->id, $consulta[0]->nombre);								
			}
		}
		
		return $resultado;
	}

	function getAll() {
		$resultado=null;
		if(($consulta=$this->_getTabla("habitacion"))!=null){
			$resultado=array();
			foreach($consulta as $item){
				$habAux=new Habitacion($item->id, $item->nombre);
				array_push($resultado, $habAux);
			}
		}
		
		return $resultado;
	}	
}	