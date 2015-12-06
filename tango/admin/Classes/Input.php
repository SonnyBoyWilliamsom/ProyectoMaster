<?php
#En esta clase se crearan metodos para comprobar si los datos de los fomularios son correctos, no vienen vacios...

class Input{
	public static function exists($type='post'){ //Comprueba que se ha enviado el formulario (Es decir si se ha generado valor (aunque sea vacio) de $_GET o $_POST)
		switch($type){
			case 'post': //Si el metodo usado es post se compruba si la variable $_POST viene vacia o no
				return (!empty($_POST)) ? true : false;
			break;
			case 'get':
				return (!empty($_GET)) ? true : false; 
			break;
			default: 
				return false;
			break;
		}
	}
	public static function get($input){ //Funcion get obtiene el valor de $_POST o $_GET dependiendo de la clave que se le pase ($input)
 		if(isset($_POST[$input]) && !empty($_POST[$input])){
			return $_POST[$input];
		}elseif(isset($_GET[$input]) && !empty($_GET[$input])){
			return $_GET[$input];
		}
		return false;
	}
	public static function getInputs($method){
		//En las siguientes lineas se selecciona aquellas entradas que no vienen vacias, de esta forma solo se hace update con datos utiles		
		$inputsVal = array();
		$keys = array();	
		
		foreach($method as $key => $input){
			if(self::get($key)){
				if($key == 'token'){
					break;
				}
				$inputsVal []= self::get($key);
				$keys []= $key;
			}
		}
		return array_combine($keys, $inputsVal);
	}

}	