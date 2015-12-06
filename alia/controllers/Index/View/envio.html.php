<?php
    include_once(getRoot() . "/controllers/Inmuebles/Controller/inmuebles.class.php");
    include_once(getRoot() . "/controllers/Formularios/Controller/formularios.class.php");
    $inmueblesController = new Inmuebles($this->db);
    $buscador=$inmueblesController->getFormulario("Buscador");
    $mensaje=($_GET['correcto']=="correcto")?"Su mensaje se envió correctamente, nos pondremos en contacto con usted lo antes posible":"Se produjo un error enviando su mensaje, por favor vuelva a intentarlo más tarde";
?>
<body>
    <?php include_once("header.php"); ?>
    <div class="buscadorFlotante" style="display:none;">
        <?php Formularios::printFormulario($buscador,$this->getDB()); ?>
    </div>
    <main>
        <div id="contactoAlia">
            <h2>Contacta con Alia</h2>
            <p><?php echo $mensaje; ?></p>
        </div>
    </main>
    <?php include_once("footer.php"); ?>
</body>
