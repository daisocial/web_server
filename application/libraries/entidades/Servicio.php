<?php

class Servicio{
	public $Nombre;
	public $Id;

	public function __construct($i=-1, $nom=null){
		$this->Nombre=$nom;
		$this->Id=$i;
	}
			
}

?>
