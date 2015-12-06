<?php
include './core/Db_model.php';
include './core/Categorias_model.php';
include './core/Contacto_model.php';
include './model/Categoria.php';
include './model/Contacto.php';

//todas las clases funcionales van a extender de main
class Main {
    
    //convertimos el attr en protected para que pueda ser accesible desde las clases que extiendan de esta, es decir, todas las clases que hereden sus atr o funciones públicas o protected
   protected $db;
    
   function __construct() {
       include 'inc/connect.php';
       $this->db = new Db_model($host, $user, $password, $database);
   } 
    
}
