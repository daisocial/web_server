<?php

class Propiedad{	
	public $Nombre;
	public $tipoAcceso;

	public function __construct($nom=null, $tipoAcc=null, $ult=-1){		
		$this->Nombre=$nom;
		$this->tipoAcceso=$tipoAcc;
		$this->ultimoValor=$ult;
	}	
}

?>
