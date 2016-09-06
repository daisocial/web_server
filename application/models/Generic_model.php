<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generic_Model extends CI_Model {
/*****************************************
	  Funciones Comunes a los modelos
******************************************/
	function _dispositivosInit($tabla, $valor){
		$str="SELECT dispositivo.id as identificador, dispositivo.value as valor,";
		$str.=" dispositivo.prop as propiedad, habitacion.nombre as habitacion,";
		$str.=" habitacion.id as id_hab, servicio.nombre as servicio, dispositivo.nombre as nombre";
		$str.=" FROM dispositivo, habitacion, servicio";
		$str.=" WHERE ".$tabla.".id=".$valor;
		$str.=" AND dispositivo.rHab=habitacion.id";
		$str.=" AND dispositivo.servicio=servicio.id";

		$consulta =$this->db->query($str);
		return $consulta;
	}

	function _getTabla($nombreTabla=null) {
		$resultado=null;
		if($nombreTabla!=null){
			$str="SELECT *  FROM ".$nombreTabla;
			$consulta =$this->db->query($str)->result();
			if($consulta!=null){
				$resultado=$consulta;
			}
		}
		
		return $resultado;
	}	

}