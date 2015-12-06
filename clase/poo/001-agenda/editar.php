<?php
include './core/Main.php';

//instanciamiento de obj de clases funcionales para poder llamar a sus funciones
$objC=new Contacto_model();
$objCat=new Categorias_model();

////////////////////////////////////////


$mng="";

if($_POST){
    extract($_REQUEST);
    $result=$objC->updateContacto($id, $nombre, $apellidos, $telefono, $email, $foto, $id_categoria);
    
    if(gettype($result) == "string"){
        $mng=$result;
    }else{
        if($result){
            $mng="Ok update";
        }else{
            $mng="Ko update";
        }
    }
}


$objContacto=$objC->selectContacto($_GET['id']);
$arrayObjCat=$objCat->selectCategorias();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h2>Editar contacto</h2>
        <form action="<?=$_SERVER['PHP_SELF']?>?id=<?=$_GET['id']?>" method="post">
            <input type="text" name="nombre" value="<?=$objContacto->getNombre()?>" placeholder="Nombre" required>
            <input type="text" name="apellidos" value="<?=$objContacto->getApellidos()?>" placeholder="Apellidos">
            <input type="text" name="telefono" value="<?=$objContacto->getTelefono()?>" placeholder="Teléfono" required>
            <input type="email" name="email" value="<?=$objContacto->getEmail()?>" placeholder="Email">
            <input type="text" name="foto" value="<?=$objContacto->getFoto()?>" placeholder="Foto">
            <select name="id_categoria">
                <?php foreach($arrayObjCat as $objCat){ ?>
                    <?php 
                    if($objCat->getId() == $objContacto->getId_categoria()){
                        $selected="selected";
                    }else{
                        $selected="";
                    }
                    ?>
                    <option value="<?=$objCat->getId()?>" <?=$selected?>>
                            <?=$objCat->getCategoria()?>
                    </option>
                <?php } ?>
            </select>
            
            <input type="submit" value="Guardar">
        </form>
        <p><?=$mng?></p>
        
    </body>
</html>
