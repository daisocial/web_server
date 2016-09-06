<?php

class Habitacion{
	public $Nombre;
	public $Id;
	public $Dispositivos;


	public function addDispositivo(Dispositivo $disp){
		array_push($this->Dispositivos, $disp);
	}

	public function __construct($i=-1, $nom=null){
		$this->Nombre=$nom;
		$this->Id=$i;
		$this->Dispositivos=array();
	}

	protected function iconoHabitacion(){
		switch ($this->Nombre) {
			case 'Exterior': $icon="fa-sun-o"; break;
			case 'Maletin': $icon="fa-briefcase"; break;
			case 'LightsPanel': $icon="fa-lightbulb-o"; break;
			case 'Sittingroom': $icon="fa-paper-plane"; break;
			case 'Workplace': $icon="fa-building"; break;
			default: $icon="fa-cube";
		}
		return $icon;
	}

	protected function traducir(){
		$es_str=$this->Nombre;
		switch ($this->Nombre) {
			case 'LightsPanel': $es_str="Panel de luces"; break;
			case 'Sittingroom': $es_str="Sala de estar"; break;
			case 'Workplace': $es_str="Oficina"; break;
		}
		return $es_str;
	}

	public function htmlHabitacion(&$i){
		$resultado="\t<a href=\"".site_url("Inicio/h/".$this->Nombre)."\" class=\"col-sm-4 col-xs-6 tileHabitacion color".($i%TOTAL_COLORES_UI)."\" >\n";
		$resultado.="\t\t<i class=\"fa ".$this->iconoHabitacion()." fa-5x\"></i>\n";
		$resultado.="\t\t<h4 class=\"titulo\">".$this->traducir()."</h4>\n";
		$resultado.="\t</a>\n";
		$i++;
		return $resultado;
	}		
}

?>