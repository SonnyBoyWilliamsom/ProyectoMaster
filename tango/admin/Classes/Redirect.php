<?php

class Redirect{
	public static function to($location = null){
		if($location){
			if(is_numeric($location)){ //con esta funcinalidad podemos tan solo pner el numero de error y nos cargara el template  correcpondiente a ese error
				switch($location){
					case 404:
						header('HTTP/1.0 404 Not Found'); //El header cuando se trata de una pagina de error no es header('location: page.php'). Despues del header el codigo se sigue ejecutando, luego podemos incluir una pagina propia
						include './inc/error/404.php'; //De esta manera nos mantenemos en la misma pagina pero mostramos la que se tiene para el error surgido
						exit();
					break;
				}
			}
			header('location: '.$location);
			exit();
		}
	}
}