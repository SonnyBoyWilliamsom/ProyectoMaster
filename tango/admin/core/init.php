<?php
session_start();
//$GLOBALS es una variable (array de arrays) con toda la informacion de configuracion de nuestro proyecto. 

$GLOBALS['config'] =array(
		'mysql' => array(
			'host' => 'localhost',
			'username' => 'root',
			'password' => 'root',
			'db' => 'tango',
			'test' => array(
				'a1' => 'hola',
				'a2' => 'adios'
				)
			),
		'remember' => array(
			'cookie_name' => 'hash',
			'cookie_expire' => 604800
			),
		'session' => array(
			'session_name' => 'id_user',
			'token_name' => 'token'
			)
	);

//Para evitar hacer un require o un include por cada clase de nuestro proyecto, podemos usar una funcion de php que permite ejecutar una clase cada vez que se llama:
spl_autoload_register(function($class){
	require_once getRoot().'Classes/'.$class.'.php';
});
require_once getRoot().'functions/clean.php';

//Check de la Cookie en caso de que exista. Si esto ocurre los datos se cargaran automaticamente dependiendo del usuario al que pertenezca la cookie
// Las sesiones pueden expirar antes que la cookies, por ello para usar los datos de la cookie hay que no estar logeado con la sesion. 
//La cookie sirve para almacenar datos de ayuda a la navegacion (tambien pueden ser datos de acceso), 
//con la sesion podemos estar accediendo directamente a nuestro perfil luego si hay sesion no tiene sentido usar cookies (por lo menos en el acceso)
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	//Se comprueba que $_COOKIE['hash'] esta establecida y $_SESSION['id_user'] no lo esta
	//Se obtiene el valor de $_COOKIE['hash'];
	$hashCookie = Cookie::get(Config::get('remember/cookie_name'));
	//Accedemos a la base de datos para comprbar si existe el valor de la cookie(hash) en users_session. Obtenemos un objeto DBcorePDO()
	$cookieDB = DBcorePDO::getInstance()->get('users_session',array('hash','=',$hashCookie)); 
	//Si existe
	//var_dump($cookieDB->getResults()[0]->user_id);
	if($cookieDB->count()>0) {

		$user = new User($cookieDB->getResults()[0]->user_id);//Obtiene el usuario con el id asociado a la cookie
		//echo 'usuario ha pedido ser recordado pero ya se le acabo la sesion';//Este mensaje no aparece mientras exista sesion
		//var_dump($user->data()); //Estos valores correcpondientes al usuario que establecio la cookie seran accesibles para el resto de la página siempre que no se destruya la cookie
		//var_dump($user);
		$user->loginUser();

	}

}
