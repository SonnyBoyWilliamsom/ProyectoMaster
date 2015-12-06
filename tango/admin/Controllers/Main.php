<?php
include '../Classes/User.php';
include './Controllers/DataBaseCorePDO.php'; 



//Para hacer una llamada a solo un controlador principal (y evitar demasiados includes en index.php) creamos la calse Main. 
//Funciones de Main:
//	- Contener todos los demas controladores (includes). Que incluya todos los demas controladores no significa que los necesite para su ejecución.
//	- Servir de controlador principal. Main establece la conexión con la bdd y todos los demas controladores que necesiten esta conexión extenderan a Main. 
//Main usa la clase DataBaseCorePDO.php para hacer la conexión.
//

class Main {
	protected $db;

	function __construct(){
		include './inc/dataConnect.php'; 
	//Los datos del servidor, usuario, contraseña y nombre de la bdd se encuentran en este fichero 
		$this->db = new DataBaseCorePDO($servername, $dbname, $username, $password);
	}
}



?>