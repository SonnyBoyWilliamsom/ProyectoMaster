<?php
require_once './functions/library.php';
require_once './core/init.php';
ini_set('display_errors','1');

$user = new User();
	$user->logout();
	Redirect::to('./index.php'); //En este caso es como si se redirigiera a la página de login

