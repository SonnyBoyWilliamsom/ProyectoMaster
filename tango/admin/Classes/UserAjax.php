<?php
require_once '../functions/library.php';
require_once '../core/init.php';

ini_set('display_errors','1');
if(Input::exists('get')){
	if(Input::get('query')){
		$userObj = new User();
		$query = Input::get('query');
		switch ($query){
			case '1':
				//echo $userObj->data()->id_user;
				//var_dump($userObj->getAllUsers());
				$users = array('users'=>$userObj->getAllUsers());
				//var_dump($users);
				$JSON = json_encode($users);
				print_r($JSON);
			break;
		}
	}else{
		echo 'no query';
	}
}else{
	echo 'no input';
}

