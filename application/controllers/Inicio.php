<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

    public function __construct(){
        parent::__construct();
    }

    public function cambiaColores($opcion){
        if(in_array($opcion, unserialize(TIPO_COLORES))){
            $this->configuracion_model->setColor($opcion);
            return "true";
        }
        return "false";
    }

    private function _tipoHoja($tipo=null){
        $resultado="hojasEstilo/hojaNormal";
        switch ($tipo) {
            case 'normal':
                $resultado="hojasEstilo/hojaNormal";            
            break;
            case 'contraste':
                $resultado="hojasEstilo/hojaContraste";             
            break;
            case 'lectura':
                $resultado="hojasEstilo/hojaLectura";               
            break;
            
        }
        return $resultado;
    }

    public function actualizarDispositivos(){
        $dispositivosEN=$this->dispositivo_model->getDispositivos();
        $i=0;
        foreach($dispositivosEN as $d){
            echo $d->htmlDispositivo($i);
        }
    }

    public function h($par=null){
        switch (strtolower($par)) {
            case 'exterior':  $this->getDispositivosHabitacion($par); break;
            case 'maletin':  $this->getDispositivosHabitacion($par); break;
            case 'lightspanel':  $this->getDispositivosHabitacion($par); break;
            case 'sittingroom':  $this->getDispositivosHabitacion($par); break;
            case 'workplace':  $this->getDispositivosHabitacion($par); break;
            default: $this->index();
        }
    }

    public function index(){
        $habitacionesEN=$this->habitacion_model->getAll();
        $i=0;
        foreach($habitacionesEN as $d){
            $datos["habitaciones"][]=$d->htmlHabitacion($i);
        }

        $c=$this->configuracion_model->getConfiguracion()->TipoColores;
        $datos['queColor']=$c;
        $this->load->view('header');
        $this->load->view($this->_tipoHoja($c));
        $this->load->view('inicio', $datos);
        $this->load->view('footer'); 
    }     

	public function inicioBody(){
        $c=$this->configuracion_model->getConfiguracion()->TipoColores;
        $datos['queColor']=$c;
        $habitacionesEN=$this->habitacion_model->getAll();
        $i=0;
        foreach($habitacionesEN as $d){
            $datos["habitaciones"][]=$d->htmlHabitacion($i);
        }        
		$this->load->view('inicio', $datos);
	}

	/*public function getDispositivosHabitacion($nombre=null){
        if($nombre!=null){
            $queHab=$this->habitacion_model->getHabitacion($nombre);
            if($queHab!=null){    
        		$dispositivosEN=$this->dispositivo_model->getDispositivos($queHab);
                $i=0;
                foreach($dispositivosEN as $d){
                    echo $d->htmlDispositivo($i);
                }
                echo "<span id=\"queHabitacion\" style=\"visibility: hidden;\">".$nombre."</span>\n";
            }
        }
	}*/

    public function getDispositivosHabitacion($nombre=null){
        if($nombre!=null){
            $queHab=$this->habitacion_model->getHabitacion($nombre);
            if($queHab!=null){    
                $dispositivosEN=$this->dispositivo_model->getDispositivos($queHab);
                $i=0;
                $groupDisp=array();
                foreach($dispositivosEN as $d){
                    $groupDisp[$d->Servicio][$d->Id]=$d;
                }
                $allServices=array_keys($groupDisp);
                foreach($allServices as $s){
                    echo "<div class=\"metro\"><span class=\"col-xs-12 separador\">";
                    echo "<h4 class=\"titulo\">".$this->_traductor($s)."</h4></span></div>";
                    foreach($groupDisp[$s] as $dg){
                        echo $dg->htmlDispositivo($i);    
                    }
                }
                echo "<span id=\"queHabitacion\" style=\"visibility: hidden;\">".$nombre."</span>\n";
            }
        }
    }

    public function cambiarDimmer($id=null){
        $color = $this->input->post('color');
        if($id!=null && $color!=null){
            $coloresRGB=$this->hex2rgb($color);
            file_get_contents(HOST_ENTORNO."/device/".$id."/write/dimmerRed/".$coloresRGB[0]);
            file_get_contents(HOST_ENTORNO."/device/".$id."/write/dimmerGreen/".$coloresRGB[1]);
            file_get_contents(HOST_ENTORNO."/device/".$id."/write/dimmerBlue/".$coloresRGB[2]);
            
            echo "cambiado";
            return 1;
        }else{
            echo "no cambiado";
            return 0;
        }
    }

    private function hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       if($r==255){
            $r=$r-1;
       }
       if($r==255){
            $g=$g-1;
       }
       if($r==255){
            $b=$b-1;
       }
       $rgb = array($r, $g, $b);
       return $rgb; // returns an array with the rgb values
    }

    private function normaliza($cadena){
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);
        return utf8_encode($cadena);
    }

	public function asr(){
        $fraseAsr = $this->input->post('asr');
		$fraseAsr = $this->normaliza($fraseAsr);
        $fraseSinEspacios=str_replace(" ", "+", $fraseAsr);
        $urlConOrden="http://".SERVIDOR_ASR."?frase=".$fraseSinEspacios;
        $data = file_get_contents($urlConOrden);
        //DEBUG
        echo $urlConOrden;
	}

    private function _traductor($frase=null){
        $frase=strtolower($frase);
        $diccionario["lighting"]="Luces";
        $diccionario["meters"]="Medidores";
        $diccionario["weather"]="Ambiente";
        $diccionario["sensing"]="Sensores";
        $diccionario["blinds"]="Estores";
        if(array_key_exists($frase, $diccionario)){
            return $diccionario[$frase];
        }
        
        return $frase;
    }
}
