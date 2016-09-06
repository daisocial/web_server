<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispositivo_Model extends Generic_model {
	public function __construct() {
		parent::__construct();
	}

	public function getDispositivos($habOServ=null) {
		if($habOServ!=null){
			if($habOServ instanceof Habitacion){
				$filtro="AND d.rHab=".$habOServ->Id." ";
			}elseif ($habOServ instanceof Servicio) {
				$filtro="AND s.id=".$habOServ->Id." ";
			}else{
				$filtro="";
			}
		}else{
			$filtro="";
		}
		$resultado=array();
		$queryDisp="SELECT d.nombre 'd_nombre', d.id 'd_id', d.tipoComponente, d.tipoDispositivo, ";
		$queryDisp.="h.nombre 'hab_nombre', s.nombre 'ser_nombre', ";
		$queryDisp.="dp.ultimoValor, p.nombre 'p_nombre', p.acceso 'p_acceso' ";
		$queryDisp.="FROM dispositivo as d, propiedad as p, disptienepropiedad as dp, ";
		$queryDisp.="habitacion as h, servicio as s ";
		$queryDisp.="WHERE dp.idDisp=d.id AND dp.idProp=p.nombre ";
		$queryDisp.="AND d.rHab=h.id AND d.servicio=s.id ".$filtro;
		$queryDisp.="ORDER BY d_id ASC, p.nombre ASC";
		$consulta =$this->db->query($queryDisp)->result();

		if($consulta!=null){
			$todasFilas=$consulta;

			$auxProp=array();
			$dAnt=$todasFilas[0];

			foreach($todasFilas as $disp){
				if($disp->d_id!=$dAnt->d_id){
					$resultado[]=$this->definirDispositivo($dAnt->d_id, $dAnt->d_nombre, $dAnt->tipoComponente, $dAnt->tipoDispositivo, $dAnt->hab_nombre, $dAnt->ser_nombre, $auxProp);
					$auxProp=array();
				}				
				$auxProp[]=new Propiedad($disp->p_nombre, $disp->p_acceso, $disp->ultimoValor);
				$dAnt=$disp; //Establecemos el valor para la siguiente iteracion
			}
			//Como se almacena al principio del bucle, insertamos la última fila que nos queda
			$resultado[]=$this->definirDispositivo($dAnt->d_id, $dAnt->d_nombre, $dAnt->tipoComponente, $dAnt->tipoDispositivo, $dAnt->hab_nombre, $dAnt->ser_nombre, $auxProp);
		}
		return $resultado;
	}

	private function definirDispositivo($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props){
		if(strrpos((strtolower($tipoDispositivo)), "dimminglighting") !== false){
			if(count($props)==7 || count($props)==6){
				return new Dimmercolores($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props);
			}
			if( count($props)==2){
				return new Dimmerbinario($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props);
			}
			if( count($props)==4){
				return new DimmerSwitch($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props);
			}			
		}

		if(strrpos((strtolower($tipoDispositivo)), "binarylighting") !== false){
			return new SwitchBinario($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props);
		}

		if(strrpos((strtolower($tipoDispositivo)), "blind") !== false){
			return new Soloescritura($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props);
		}				
			
		if(strrpos((strtolower($tipoDispositivo)), "energymeter") !== false || 
			strrpos((strtolower($tipoDispositivo)), "weatherstation") !== false || 
			strrpos((strtolower($tipoDispositivo)), "motionsensor") !== false ||
			strrpos((strtolower($tipoDispositivo)), "tempsensor") !== false){
			return new Solodatos($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props);
		}
		return new Dispositivo($d_id, $d_nombre, $tipoComponente, $tipoDispositivo, $habtiacion, $servicio, $props);
	}	


}