<?php
ini_set('display_errors', '1');
require_once './functions/library.php';
require_once './core/init.php';

ini_set('display_errors','1');

$user = new User();

if($user->isLoggedIn()){
	if(Input::exists('post')){
		
		if(Token::check(Input::get('token'))){
			$validate = new Validate();
			$validate->check($_POST, array(
				'name'=>array(
					'required'=>true,
					'uniqueInverse'=>array('users',$user->data()->id_user),
					'min'=>2,
				),
				'surname'=>array(
					'min'=>2
				), 
				'site'=>array(
					'min'=>2
				),
				'field'=>array(
					'min'=>2
				)
			));

			if($validate->passed()){
				//echo 'validate passed you now can update';
				 //array que contiene solo las entradas validas (no vacias) a modo de array asociativo
				//var_dump(Input::getInputs($_POST));
				//var_dump(array('id_user'=>$user->data()->id_user));
				//$cond = array('id_user'=>'42');
				//echo gettype($cond);
				//var_dump(Input::getInputs($_POST));
				$user->update(array('id_user'=>$user->data()->id_user),Input::getInputs($_POST));
				
				//Redirect::to('./');
			}else{
				foreach ($validate->errors() as $error) {
					echo $error.'<br>';
				}
			}
		}
	}else{
		echo 'No se envio el formulario';
	}

}else{
	Redirect::to('./login.php');
}

	include './views/update.html';


