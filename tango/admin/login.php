<?php
require_once './functions/library.php';
require_once './core/init.php';
//primero hay que comprobar que existe el token
ini_set('display_errors','1');

if(Token::check(Input::get('token'))){
	//Despues se comprueba si se ha enviado el formulario evaluando si la variable $_POST se ha establecido (aun siendo cadena vacia)
	if(Input::exists('post')){ 
		//Despues se pasan a validar los datos del formulario. Una comprobacion simple seria:
		/*
		if(!Input::get('email')){
			echo 'email es requerido';
		}
		Pero con la clase Validate.php se pueden comprobar mas atributos de una misma entrada, si es email valido, caracteres minimos o maximos, etc
		*/
		$validLogin = new Validate();
		$validLogin->check($_POST, array(
			'email' => array(
				'required' => true,
				'email' => true
				),
			'password' => array(
				'required' => true,
				'min' => 6
				)
		));
		if($validLogin->passed()){
			$user = new User();
			$remember = (Input::get('remember') === 'on') ? true : false;
			//Si el usuario selecciona la casilla de recordar, este valor se le pasara como parametro de entrada al metodo loginUser()
			$login = $user->loginUser(array('email'=>Input::get('email')), escape(Input::get('password')), $remember) ;
			if($login){
				echo '200';//User found and data validate on server ok
				//Redirect::to('./');
				//En caso de que el token proporcionado exista en sesion, lo borramos y devolvemos true (si existia)
				//Se destruye aqui ya que con ajax el mismo form se enviara mas veces hasta que el acceso sea correcto
				Session::delete(Config::get('session/token_name'));
			}else{
				echo '404'; //Email or password wrong. 
			}
		}else{
			echo '400'; //Bad request, data was not introduced properly (not valid email, password very short)
			/*foreach($validLogin->errors() as $error){
				
				//echo $error.'<br>';
			}*/
		}

	}
}else{
	//echo 'Problema con el token';
	//echo Input::get('token');
	echo '511';//Error con el token
}
	
//include './views/login.html';

?>