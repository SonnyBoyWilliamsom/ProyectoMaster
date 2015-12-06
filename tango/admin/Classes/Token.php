<?php

class Token {
	#Cross-site request forgery (Solicitud cruzada falseada en sitios web) es la practica de hacer solicitudes desde la url en otro sitio web o en el mismo sitio. 
	#Para prevenir esta practica se genera un token que es como una llave que se le asigna al elemento que va a realizar la accion 
	#de tal manera que solo si se tiene acceso a esa llave se podrá hacer la solicitud correspondiente

	#El formulario o elemento que vaya a necesitar un token llamara al metodo generate () de la clase Token.php. Este metodo asocia un valor aleatorio unico (uniqid) a dicho elemento
	#y a su vez lo almacena en sesion ($_SESSION['token'] pasa a valer md5(uniqid()).

	public static function generate(){ //Generacion del token
		Session::delete(Config::get('session/token_name'));
		return Session::put(Config::get('session/token_name'), md5(uniqid())); //Se guardará en $_SESSION['token'] 
	}

	#Creando un token para cada elemento de accion no es suficiente para la proteccion de CSRF. Como ya tenemos el token almacenado en sesion hay  que ser
	#capaces de comprobar si ese token es valido para realizar cualquier accion
	public  static function check($token){
		//$tokenKey = Config::get('session/token_name'); //Obtencion del valor del token que habia en sesion 
		if(Session::exists(Config::get('session/token_name')) && $token === Session::get(Config::get('session/token_name'))){ //comprobacion de si el token que hemos proporcionado coincide con el de sesion
			//En caso de que el token proporcionado exista en sesion, lo borramos y devolvemos true (si existia)
			//Session::delete($tokenKey);
			return true;
		}
		return false;
	}
}