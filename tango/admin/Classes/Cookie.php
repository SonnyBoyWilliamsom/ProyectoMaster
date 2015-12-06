<?php
class Cookie{
	//Las cookies sirven para recordar los datos de acceso del usuario (se puede establecer con la contraseña para llevar al usuario a la pagina pricipal directamente)

	public static function exists($name){
		return (isset($_COOKIE[$name])) ? true:false;
	}

	public static function get($name){
		return $_COOKIE[$name];
	}

	public static function put($name, $value, $expiry){
		
		if(setcookie($name, $value, time() + $expiry, '/')){
			return true;
		}
		return false;
	}

	public static function delete($name){
		self::put($name, '', time()-1);
	}

}

