<?php

class Session{

	public static function exists($name){
		return (isset($_SESSION[$name])) ? true : false;
	}

	public static function get($name){
		return $_SESSION[$name];
	}

	//Con este metodo se asigna a un valor $value a $_SESSION[$name]. Para el caso de la generacion de un token, cada vez el valor $_SESSION['token_name'] sera diferente
	public static function put($name, $value){ 
		return $_SESSION[$name]=$value;
	}

	public static function delete($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}

	public static function flash($name, $content =''){
		if(self::exists($name)){
			$session = self::get($name);
			self::delete($name);
			return $session;
		}else{
			self::put($name,$content);
		}

	}

}