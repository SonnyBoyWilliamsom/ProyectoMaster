<?php
include_once(getRoot() . "/controllers/Base/View/base_admin.html.php");
include_once(getRoot() . "/controllers/Demandas/Controller/demandas.class.php");

$idDemanda=$_GET['id_demanda'];
$demandas=new Demandas($this->db);
$demandas->cargar();
$this->cargar();
$tareas=$this->buscadorAvanzado(array("id_demanda"=>$idDemanda));
$demanda=$demandas->buscar("id",$idDemanda);
?>
<body>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <?php include_once("bloque-exportacion.php"); ?>
        <?php include_once("bloque-importacion.php"); ?>
        <?php include_once("bloque-busqueda-nuevo.php") ?>

        <?php Listados::printListado($mostrar,$this); ?>
    </div>
</body>
