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
			$validation = $validate->check($_POST, array(
				'currentPassword'=>array(
					'min'=>6
				),
				'newPassword'=>array(
					'required'=>true,
					'min'=>6
				),
				'newPassCheck'=>array(
					'min'=>6,
					'matches'=>'newPassword'
				),
				'email'=>array(
					'required'=>true,
					'email'=>true,
					'uniqueInverse'=>array('users',$user->data()->id_user),
				),
				'passwordEmail'=>array(
					'min'=>6
				)
			));
			if($validation->passed()){
				
				//echo $user->data()->password.'<br>';
				//echo Hash::make(Input::get('currentPassword'),$user->data()->salt);
				if(Input::get('currentPassword') != ''){
					if(Hash::make(Input::get('currentPassword'),$user->data()->salt) == $user->data()->password) {
						//var_dump(Input::getInputs($_POST));
						//echo Input::get('newPassword');
						//$newPass = Hash::make(Input::get('newPassword'), $user->data()->salt);
						//echo $newPass;
						
						if($user->update(array('id_user'=>$user->data()->id_user), array('password'=>Hash::make(Input::get('newPassword'), $user->data()->salt)))){
							echo 'UPDATED';
						}

					}else{
					echo 'Wrong password!';
					}
				}elseif(Input::get('passwordEmail') != ''){
					echo 'se va a acctualizar email';
					if(Hash::make(Input::get('passwordEmail'),$user->data()->salt) == $user->data()->password) {
						//var_dump(Input::getInputs($_POST));
						//echo Input::get('newPassword');
						//$newPass = Hash::make(Input::get('newPassword'), $user->data()->salt);
						//echo $newPass;
						
						if($user->update(array('id_user'=>$user->data()->id_user), array('email'=>Input::get('email')) )) {
							echo 'email UPDATED';
						}

					}else{
					echo 'Wrong password!';
					}
				}else{
					echo 'no va a actualizar nada';
				}
				

				
			}else{
				foreach ($validation->errors() as $error) {
					echo $error.'<br>';
				}
			}
		}
	}
}

include './views/updatePass.html';