<?php
require_once '../functions/library.php';
require_once '../core/init.php';
//primero hay que comprobar que existe el token
ini_set('display_errors','1');
$user = new User();
if($user->isLoggedIn()){
	    Redirect::to('../../');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        .navbar{
            margin-bottom: 0px;
        }
    </style>
  
    <title>Tango</title>

    <link href="../../css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/bootstrap/css/jquery.fancybox.css" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../../js/bootstrap_js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap_js/jquery.fancybox.pack.js"></script>

    <script>
    $(document).ready(function(){
    	showForms();
        function showForms(){
        	console.log(window.location.pathname);
        	if(window.location.pathname === '/admin/login/'){
        	$('.loginLight').removeAttr('style');
        	
        	}
        }

        $('#loginForm').submit(function(){
        	var dataLogin = $(this).serialize();
        	$.post('../login.php',dataLogin, processLogin);//Esta es otra forma de manejar datos con el metodo ajax, en lugar de $.ajax()

        	function processLogin(data){
        		switch (data){
        			case '200':
        			console.log(data);
						location.href='../';
        			break;
        			case '404': 
                        $('.warnData').css('display','none');
                        $('.warnPass').removeAttr('style');
                        //$("#loginForm")[0].reset();
                        //$(this).prepend('<p>Su email y contraseña no coinciden, por favor intentelo de nuevo.</p>');
                    break;
                    case '400':
                        $('.warnPass').css('display','none');
                        $('.warnData').removeAttr('style');
                        $("#loginForm")[0].reset();
        		} 


        		/*if(data==200){
        			location.href='../';
        		}else{
        			alert('Wrong User');
        		}*/
        	}
        	return false; //no olvidar al hacer nulo el envio del formulario, ya que toda la inforamacion se envia y se obtiene por AJAX

        });//end submit
   });


   

    </script>
    
</head>

<body>

<?php include '../views/login.html'; ?>


</body>
</html>
