<?php

class Dimmercolores extends Dispositivo{

	protected function iconoServicio(){
		return "fa-paint-brush";
	}

	private function _coloresDimmerHEX(){
		$resultadoAux[]=-1;
		$resultadoAux[]=-1;
		$resultadoAux[]=-1;
		foreach($this->Propiedades as $p){
			if($p->Nombre=="dataDimmerRed"){
				$resultadoAux[0]=dechex($p->ultimoValor);
			}
			if($p->Nombre=="dataDimmerGreen"){
				$resultadoAux[1]=dechex($p->ultimoValor);
			}	
			if($p->Nombre=="dataDimmerBlue"){
				$resultadoAux[2]=dechex($p->ultimoValor);
			}						
		}

		if(array_search(-1, $resultadoAux) || ($resultadoAux[0]==0 && $resultadoAux[1]==0 && $resultadoAux[2]==0)){
			return "128, 128, 128";
		}
		return $resultadoAux[0].", ".$resultadoAux[1].", ".$resultadoAux[2];
	}

	public function htmlDispositivo(&$i){
		
		$resultado="\t<span data-tts=\"Seleccionar color ".$this->Nombre."\" class=\"col-sm-2 col-xs-6 tileColor color".($i%TOTAL_COLORES_UI)."\" >\n";
		//$resultado.="\t\t<i class=\"fa ".$this->iconoServicio()." fa-5x\"";
		//$resultado.=" style=\"color: rgb(".$this->_coloresDimmerRGB().");\" ></i>\n";
		$resultado.="<form><input type=\"color\" value=\"#".$this->_coloresDimmerHEX()."\" name=\"".$this->Id."\" onchange=\"cambiarColorInput(this)\"></form>";
		$resultado.="\t\t<h4 class=\"titulo\">".$this->Nombre."</h4>\n";
		$resultado.="\t</span>\n";
		$i++;
		return $resultado;
	}


}

?>