<?php

class SwitchBinario extends Dispositivo{

	private function _getRead(){

		foreach ($this->Propiedades as $pr) {
			if($pr->tipoAcceso=="READ"){
				return $pr;
			}
		}
	}

	public function htmlDispositivo(&$i){
		if($this->_getRead()->ultimoValor==1 || $this->_getRead()->ultimoValor=='true'){
			$esActivo="activo";
			$estado="activado";
		}else{
			$esActivo="inactivo";
			$estado="desactivado";
		}
		$datosTTS=$this->Nombre." ".$estado;
		$resultado="\t<a data-tts=\"".$datosTTS."\" href=\"".HOST_ENTORNO.$this->enlaceToogle()."\" class=\"".$esActivo." col-sm-2 col-xs-6 tile color".($i%TOTAL_COLORES_UI)."\" >\n";
		$resultado.="\t\t<i class=\"fa ".$this->iconoServicio()." fa-5x \"></i>\n";
		$resultado.="\t\t<h4 class=\"titulo\">".$this->_beautifulNombre($this->Nombre)."</h4>\n";		
		$resultado.="\t</a>\n";
		$i++;
		return $resultado;
	}

}

?>