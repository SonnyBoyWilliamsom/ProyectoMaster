<?php

//Definición de las variables
$host="localhost";
$user="root";
$password="";
$database="m108_blog";

//1-Establecemos conexión
$link = mysqli_connect($host, $user, $password) or die ("Error en la conexión con el servidor");
//2-Seleccionar bbdd
mysqli_select_db($link, $database) or die ("Error en la selección de la base de datos $database");

//función que establece codificación a la hora de hablar con el servidor, para así garantizar que todo lo que insertemos, modifiquemos o pidamos se haga en base a utf8
mysqli_set_charset($link, "utf8");