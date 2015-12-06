<?php 
ini_set('display_errors','1');
require_once './functions/library.php';
require_once './core/init.php'; 
$user = new User();
(!$user->isLoggedIn()) ? Redirect::to('./login/'):false;
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

    <link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/bootstrap/css/jquery.fancybox.css" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="../js/bootstrap_js/bootstrap.min.js"></script>
    <script src="../js/bootstrap_js/jquery.fancybox.pack.js"></script>
    <script src="../js/main.js"></script>


    
</head>

<body>
        <h1>Welcome to Tango!</h1>
        <?php    
            if($user->isLoggedIn()){
        ?>

        <p>Hello <?php echo ucfirst(escape($user->data()->name)); ?>. <a href="./logout.php" title="Logout">Logout</a></p>
        <p><?php echo ucfirst(escape($user->data()->name)); ?>. <a href="./update.php" title="Update">Update</a></p>

        <?php
            }
            echo getRoot();
        ?>
        <div id="charts" class="infoUser"></div>
   


        <?php
        echo 'Dentro de PHP proyecto Fin de Master'
        ?>
    </body>
    
</html>