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
    <script src="./js/main.js"></script>
  
    
</head>

<body>

<?php include '../views/login.html'; ?>


</body>
</html>
