<?php

class SoloEscritura extends Dispositivo{

	public function htmlDispositivo(&$i){
		$resultado=$this->_htmlDispositivo($i, 1);
		$resultado.=$this->_htmlDispositivo($i, 2);
		return $resultado;
	}

	private function _htmlDispositivo(&$i, $valor){
		if($valor==1){
			$acc="<i class=\"fa fa-arrow-up fa-1x\"></i>";
			$estado="subir";
		}else{
			$acc="<i class=\"fa fa-arrow-down fa-1x\"></i>";
			$estado="bajar";
		}
		$datosTTS=$estado." ".$this->_beautifulNombre($this->Nombre);
		$resultado="\t<a data-tts=\"".$datosTTS."\" href=\"".HOST_ENTORNO.$this->_enlaceToogle($valor)."\" class=\"col-sm-2 col-xs-6 tile color".($i%TOTAL_COLORES_UI)."\" >\n";
		$resultado.="\t\t<i class=\"fa fa-align-justify fa-5x \"></i>\n";
		$resultado.="\t\t<h4 class=\"titulo\">".$acc." ".$this->_beautifulNombre($this->Nombre)."</h4>\n";
		$resultado.="\t</a>\n";
		$i++;
		return $resultado;
	}

	protected function _enlaceToogle($valor){
		$res=str_replace("{id_device}",$this->Id, ESCRIBIR_DISPOSITIVO);
		$res=str_replace("{value}", $valor, $res);
		$res=str_replace("{prop}", "moveBlind", $res);
		return $res;
	}	
}

?>