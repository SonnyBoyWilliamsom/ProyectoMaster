<?php

class Hash{
	//Con esta clase se pretende reforzar la seguridad de las contraseñas. 
	//Para toda contraseña se necesita una encriptacion que la proporciona la funcion de php hash() a la que podemos especificar el tipo de encriptación que queremos
	//SALT: es un valor adicional que se le añade a la contraseña. Esto permite tener dos contraseñas iguales pero con encriptación diferente, por ejemplo
	/*
	-Contraseñas sin SALT:
		Password1= pass1234 -> encriptacion = 123456789
		Password2= pass1234 -> encriptacion = 123456789

		Es decir, si dos usuarios diferentes tuviesen codificaciones iguales significa que ambos tinenen la misma contraseña. Esto es algo peligroso.
	
	-Contraseñas con SALT:
		Password1= pass1234%$·"!" -> encriptacion = 74hgfryfr7485e3n
		Password2= pass1234?=)(/& -> encriptacion = 93ijf8457gfh839f

		Es decir a las contraseñas (pass1234) se les añade una cadena diferente cada vez, de forma que la codificacion de dos contraseñas iguales nunca va a ser la misma. Esto es muy seguro
	
	Hay que tener en cuenta que a la hora de obtener la contraseña de nuevo tenemos que saber cual era el SALT que se añadio a la contraseña, es decir, si no se almacena el valor de SALT en la base de datos despues las contraseñas serian irrecuperables
	*/
	public static function make($string, $salt=''){
		//Encriptacion de la contraseña mas el SALT
		return hash('sha256', $string.$salt); 
	}

	//El metodo salt($length) permite crear una cadena aleatoria de $lenght caracteres. Para ello se usa la funcion mcrypt_create_iv() de php
	public static function salt($length){
		return rand(1,pow(2, $length));
	}
	public static function unique(){
		return self::make(uniqid());
	}
}