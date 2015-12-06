<?php
class DataBaseCorePDO{
    private $servername, $username, $password, $dbname; //variables usadas solo dentro de la clase
    private $base; //Variable $base sirve para poder usar el link de conexion dentro de la calse. Llamada $this->base

    function __construct($servername, $dbname, $username, $password ){ //funcion que permite la creacion de un objeto siempre que se llama a la clase (constructor)
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        
        try{
            $this->base = new PDO('mysql:host='.$this->servername.';dbname='.$this->dbname, $this->username, $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
             $this->base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Successful connection';
        }
        catch(Exception $e){
            die('Error :'. $e->GetMessage());
        }
    }
}


