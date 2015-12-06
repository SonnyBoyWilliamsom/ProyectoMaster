<?php
	function escape($string){ //Para limpiar (codificarlos de manera legible informaticamente hablando) los datos que entran y salen de la base de datos (comillas, guiones, slashes, simbolos...)
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
		//return htmlentities(addcslashes($string), ENT_QUOTES, 'UTF-8');
	}
?>