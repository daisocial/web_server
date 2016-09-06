<?php

class Solodatos extends Dispositivo{
	protected function iconoServicio(){
		switch ($this->Servicio) {
			case 'Meters': $icon="fa-tachometer"; break;
			case 'Sensing': $icon="fa-eye"; break;
			case 'Weather': $icon="fa-umbrella"; break;
		}
		if($this->Nombre=="Manubrio"){
			$icon="fa-hand-rock-o";
		}
		return $icon;
	}

	public function htmlDispositivo(&$i){
		$resultado="";
		foreach($this->Propiedades as $p){
			$datosTTS=$this->Nombre." ".$this->_beautifulNombre($p->Nombre)." ".$p->ultimoValor;
			$resultado.="\t<a data-tts=\"".$datosTTS."\" href=\"#\" style=\"cursor:default;\" class=\"col-sm-2 col-xs-6 tile color".($i%TOTAL_COLORES_UI)."\" >\n";
			$resultado.="\t\t<i class=\"fa ".$this->iconoServicio()." fa-3x\" ></i>\n";
			$resultado.="\t\t<h5>".$this->Nombre."</h5>\n";
			$resultado.="\t\t<h1>".$p->ultimoValor."</h1>\n";
			$resultado.="\t\t<h4 class=\"titulo\">".$this->_beautifulNombre($p->Nombre)."</h4>\n";
			$resultado.="\t</a>\n";
			$i++;
		}
		return $resultado;
	}	
}

?>