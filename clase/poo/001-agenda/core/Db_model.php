<?php


class Db_model {
    private $host,$user,$password,$database;
    private $link; //atributo forzado para que toda las funciones puedan acceder a esa información que se le da en la función de conexion
    
    function __construct($host, $user, $password, $database) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }
    
    private function connectDB(){
        $this->link = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        mysqli_query($this->link, "SET NAMES 'utf8'");
    }
    
    private function disconnectDb(){
        mysqli_close($this->link);
    }
    
    public function executeQuery($sql){
        $this->connectDB();
        $result=mysqli_query($this->link, $sql);
        $this->disconnectDb();
        
        return $result;
    }
    
    public function executeSelectQuery($sql){
        $this->connectDB();
        $result=mysqli_query($this->link, $sql);
        $this->disconnectDb();
        
        $nfilas=  mysqli_num_rows($result);
        
        $datos=array();
        for($i=0;$i<$nfilas;$i++){
            $datos []= mysqli_fetch_array($result);
        }
        return $datos;
        
    }
    
    
}
