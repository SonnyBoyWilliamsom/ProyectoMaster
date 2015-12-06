<?php
require_once './functions/library.php';
require_once './core/init.php';
//Primero comprobamos que el formulario viene con datos, usando el metodo estatico de la clase Input.php
ini_set('display_errors','1');
if(Session::exists('registeredBad')){
    echo Session::flash('registeredBad');
}
if(Input::exists('post') ){ //solo si en sesion se ha guardado el token del formulario y coincide con de entrada realizamos el regisstro
	if(Token::check(Input::get('token')) ) { //Con Input class solo comprobamos que se han enviado datos por el formulario, sin importar que datos (pueden ser vacios)
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50,
				'unique' => 'users', //Unico solo en la tabla useres
				'valid' => true //Permite veriicar que el nombre son solo letras y espacios
			),
			'password' => array(
				'required' => true,
				'min' => 6
			),
			'passCheck' => array(
				'required' => true,
				'matches' => 'password'
			),
			'email' => array(
				'required' => true,
				'min' => 3,
				'max' => 100,
				'unique' => 'users', //Unico solo en la tabla useres
				'email' => true //Permite verificar que el email tenga formato valido
			)
		));
		if($validation->passed()){
			$user = new User(); //Con este instanciamoento tenemos conexion con la base de datos para poder trabajar con ella
			//Creamos el SALT para concatenarlo a la contraseña en la encriptacion
			$salt = Hash::salt(62);
			//die($salt);
			if($user->createUser(array(
					'name'=> Input::get('name'),
					'surname'=> Input::get('surname'),
					'email'=>Input::get('email'),
					'site'=>'',
					'field'=>'',
					'date_register'=> date('Y/m/d'),
					'password'=> Hash::make(Input::get('password'), $salt),
					'salt' => $salt
				))){
					Session::flash('success', 'Registered done!');
					$idNewUser = $user->findUser(array('email'=>Input::get('email')))->data()->id_user; //Obtencion del id del usuario que se acaba de crear para poder establecer con el $_SESSION['id_user']

					Session::put(Config::get('session/session_name'), $idNewUser);//Dado que ya se tiene el id del nuevo usuario se puede iniciar una sesion y redirigir al usuario directamente al interior de la plataforma sin necesidad 
					Redirect::to('./');
				}else{
					Session::flash('registeredBad', 'Something went wrong!');
				}
			
		}else{
			foreach ($validation->errors() as $error) {
				print $error.'<br>';
			}	
		}

	}
}
include './views/register.html';
?>