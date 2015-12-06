<?php
include './core/Main.php';

//instanciamiento de obj de  clases funcionales para poder llamar a sus funciones
$objC=new Contacto_model(); //paralelamente se instancia un obj de DB_model en su constructor
$objCat=new Categorias_model();

//////////////////////////////////////////////

$mng="";
if($_POST){
    extract($_POST);
    $result=$objC->insertContacto($nombre, $apellidos, $telefono, $email, $foto, $id_categoria);
    if(gettype($result) == "string"){
        $mng=$result;
    }else{
        if($result){
            $mng="Ok insert";
        }else{
            $mng="Ko insert";
        }
    }
}

if(isset($_GET['a'])){
    $objC->deleteContacto($_GET['id']);
}

$arrayObjContact=$objC->selectContactos(); //al llamar a la función de la clase Contacto_model, ésta pueda llamar paralelamente a una función que se encuentra en la clase Db_model
$arrayObjCategoria=$objCat->selectCategorias();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        
        <h2>Nuevo contacto</h2>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos">
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="foto" placeholder="Foto">
            <select name="id_categoria">
                <?php foreach($arrayObjCategoria as $objCat){ ?>
                <option value="<?=$objCat->getId()?>">
                        <?=$objCat->getCategoria()?>
                </option>
                <?php } ?>
            </select>
            
            
            <input type="submit" value="Guardar">
        </form>
        
        <p><?=$mng?></p>
        
        <hr>
        
        <h1>Contactos</h1>
        
        <?php 
        foreach($arrayObjContact as $objContacto){ ?>
            <p><?=$objContacto->getNombre()." ".$objContacto->getApellidos()?><br>
               <?=$objContacto->getTelefono()." | ".$objContacto->getEmail()?><br>

               <?php 
               foreach($arrayObjCategoria as $objCat){ 
                   if($objCat->getId() == $objContacto->getId_categoria()){ ?>
                        <span><?=$objCat->getCategoria()?></span>
                  <?php }
               } 
               ?>
               <a href="editar.php?id=<?=$objContacto->getId()?>">
                Editar
               </a>
               <a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$objContacto->getId()?>&a=d">
                Eliminar
               </a>
            </p>
        <?php } ?>
        
    </body>
</html>
