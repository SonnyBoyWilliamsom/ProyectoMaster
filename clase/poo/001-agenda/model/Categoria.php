<?php

class Categoria {
    private $id,$categoria;
    
    function __construct($id, $categoria) {
        $this->id = $id;
        $this->categoria = $categoria;
    }
    function getId() {
        return $this->id;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }


}
