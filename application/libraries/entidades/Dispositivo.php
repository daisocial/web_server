<?php

class Dispositivo{
		public $Id;		
		public $Nombre;
		public $tipoComponente;
		public $tipoDispositivo;
		public $Habitacion;
		public $Servicio;
		public $ultimoValor;
		public $Propiedades;

	public function __construct($i=-1, $nom=null, $tipoComp=null, $tipoDisp=null, $h=null, $s=null, $propieda=array()){
		$this->Id=$i;		
		$this->Nombre=$nom;
		$this->tipoComponente=$tipoComp;
		$this->tipoDispositivo=$tipoDisp;
		$this->Habitacion=$h;
		$this->Servicio=$s;
		//$this->ultimoValor=0;
		$this->Propiedades=$propieda;
	}

	public function addPropiedad(Propiedad $prop){
		if($prop!=null){
			array_push($this->Propiedades, $prop);
		}
	}

	protected function iconoServicio(){
		switch ($this->Servicio) {
			case 'Alarm': $icon="fa-bell"; break;
			case 'Blinds': $icon="fa-align-justify"; break;
			case 'Lighting': $icon="fa-lightbulb-o"; break;
			case 'Meters': $icon="fa-tachometer"; break;
			case 'Sensing': $icon="fa-eye"; break;
			case 'Weather': $icon="fa-umbrella"; break;
			default: $icon="fa-cube";
		}
		return $icon;
	}

	public function htmlDispositivo(&$i){
		$resultado="\t<a href=\"".HOST_ENTORNO.$this->enlaceToogle()."\" class=\"col-sm-2 col-xs-6 tile color".($i%TOTAL_COLORES_UI)."\" >\n";
		$resultado.="\t\t<i class=\"fa ".$this->iconoServicio()." fa-5x\"></i>\n";
		$resultado.="\t\t<h4 class=\"titulo\">".$this->Nombre."</h4>\n";
		$resultado.="\t</a>\n";
		$i++;
		return $resultado;
	}

	protected function _beautifulNombre($str){
		$partes=explode(" ", $str);
		if(count($partes)==1){
			$resultado="";
			$pieces = preg_split('/(?=[A-Z]|\d+)/',$str);
			foreach($pieces as $prt){
				if($prt!="data"){
					$resultado.=$prt." ";
				}
			}
			return $resultado;
		}else{
			return $str;
		}
	}		

	protected function enlaceToogle(){
		foreach($this->Propiedades as $p){
			if(strpos(strtolower($p->Nombre), "switch")!==false && strpos(strtolower($p->tipoAcceso), "write")!==false){
				$res=str_replace("{id_device}",$this->Id, INVERTIR_DISPOSITIVO);
				$res=str_replace("{prop}", $p->Nombre, $res);
				return $res;
			}
		}
		return "#";
	}

			
}

?>
