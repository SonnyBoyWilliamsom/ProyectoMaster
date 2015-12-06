<?php
//clase encapsulada = clases con atributos privados y funciones públicas, con la finalidad de impedir el acceso directo a los atributos, evitando así un valor inapropiado para ellos.
class Contacto {
    //atibutos privados -> corresponden a cada una de las columnas de la bbdd de esa tabla que representa la clase
    private $id,$nombre,$apellidos,$telefono,$email,$foto,$id_categoria;
    
    //constructor = función que será invocada automaticamente al instanciar el objeto, dando valor a sus atributos
    function __construct($id, $nombre, $apellidos, $telefono, $email, $foto, $id_categoria) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->foto = $foto;
        $this->id_categoria = $id_categoria;
    }

    //Funciones GET/SET = funciones que realizan operaciones de lectura y escritura sobre los atributos (obtenedor/establecedor)
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellidos() {
        return $this->apellidos;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getEmail() {
        return $this->email;
    }

    function getFoto() {
        return $this->foto;
    }

    function getId_categoria() {
        return $this->id_categoria;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

    function setId_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }


    
}
