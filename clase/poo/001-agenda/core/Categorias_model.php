<?php

class Categorias_model extends Main {
    
   //attr db, constructor lo heredan de la clase principal Main
    
    function selectCategorias(){
        $arrayArrays=$this->db->executeSelectQuery("select * from categorias order by categoria asc");
        
        $arrayObjs=array();
        foreach($arrayArrays as $fila){
            extract($fila);
            $arrayObjs []= new Categoria($id, $categoria);
        }
        
        return $arrayObjs;
    }
}
