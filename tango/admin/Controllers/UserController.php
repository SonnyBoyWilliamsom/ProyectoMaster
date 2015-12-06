<?php

class UserController extends Main {
   //Para que una función pueda acceder a un dato, o se le pasa cómo parámetro de entrada o se define global, o en el caso de las clases se le asigna el valor a uno de los atributos de la propia clase
    
   //attr db, constructor lo heredan de la clase principal Main
    public function insertUser($name, $surname, $email, $site, $field, $date_registred, $password){
        if(!existUser($email)){
            if((isset($name) && !empty($name)) && (isset($surname) && !empty($surname)) && (isset($email) && !empty($email)) && (isset($site) && !empty($site)) && (isset($password) && !empty($password))){
                $query = $this->db->prepare('insert into users (name, surname, email, site, field, date_ragistred, password) values (?,?,?)');
                $query->bindParam(1, PDO::PARAM_STR, $name);
                $query->bindParam(2, PDO::PARAM_STR, $surname);
                $query->bindParam(3, PDO::PARAM_STR, $email);
                $query->bindParam(4, PDO::PARAM_STR, $site);
                $query->bindParam(5, PDO::PARAM_STR, $field);
                $query->bindParam(6, PDO::PARAM_STR, $date_registred);
                $query->bindParam(7, PDO::PARAM_STR, $password);
            }

        }else{
            return "¡Usuario ya registrado con este email!";
        }

    }
    
    public function existUser($email){
        if(isset($email) && !empty($email)){
            $query = $this->db->prepare('select * from users where email = ?');
            $query->bindParam(1, PDO::PARAM_STR, $email);
            if($query->rowCount()>0){
                return true;
            }else{
                return false;
            }
        }
    }
    
}
