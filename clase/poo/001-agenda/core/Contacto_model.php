<?php

class Contacto_model extends Main {
   //Para que una función pueda acceder a un dato, o se le pasa cómo parámetro de entrada o se define glbal, o en el caso de las clases se le asigna el valor a uno de los atributos de la propia clase
    
   //attr db, constructor lo heredan de la clase principal Main
    
   public function insertContacto($nombre,$apellidos,$telefono,$email,$foto,$id_categoria){
       if(!$this->comprobarExitsContact($telefono,NULL)){
           return $this->db->executeQuery("insert into contactos (nombre,apellidos,telefono,email,foto,id_categoria) values ('$nombre','$apellidos','$telefono','$email','$foto',$id_categoria)");
       }else{
           return "Ya existe";
       }
   }
   
   public function deleteContacto($id){
       return $this->db->executeQuery("delete from contactos where id=$id");
   }
   
   public function updateContacto($id,$nombre,$apellidos,$telefono,$email,$foto,$id_categoria){
            
            if(!$this->comprobarExitsContact($telefono, $id)){
                return $this->db->executeQuery("update contactos set nombre='$nombre',apellidos='$apellidos',telefono='$telefono',email='$email',foto='$foto',id_categoria='$id_categoria' where id=$id");
            }else{
                return "Ya existe";
            }
       
   }
   
   private function comprobarExitsContact($telefono,$id){
            if(isset($id)){ //update
                $arrayArray=$this->db->executeSelectQuery("select * from contactos where telefono='$telefono' and id!=$id");
            }else{ //insert
                $arrayArray=$this->db->executeSelectQuery("select * from contactos where telefono='$telefono'");
            }
       
            if(count($arrayArray)>0){
                return true;
            }else{
                return false;
            }
   }
   
   public function selectContacto($id){
       $arrayArray=$this->db->executeSelectQuery("select * from contactos where id=$id");
       foreach($arrayArray as $fila){
           extract($fila);
           return new Contacto($id, $nombre, $apellidos, $telefono, $email, $foto, $id_categoria);
       }
   }
   
   public function selectContactos(){
       $arrayArrays=$this->db->executeSelectQuery("select * from contactos");
       
       $arrayObj=array();
       foreach($arrayArrays as $fila){
           extract($fila);
           $arrayObj []= new Contacto($id, $nombre, $apellidos, $telefono, $email, $foto, $id_categoria);
       }
       
       return $arrayObj;
   }
   
}
