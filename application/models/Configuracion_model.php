<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion_Model extends Generic_Model {
	public function __construct(){
		parent::__construct();
	}

	public function getConfiguracion(){
		$resultado=null;
		if(($consulta=$this->_getTabla("configuracion"))!=null && count($consulta)==1){
			$resultado=new Configuracion($consulta[0]->colores);
		}
		return $resultado;
	}

	public function setColor($color=null){
		if($color!=null){
			$str="UPDATE configuracion SET colores='".$color."' WHERE id=0";
			$this->db->query($str);
			return 1;
		}
		return 0;
	}
}