<?php
    $asociate=$this->getFormulario("Asociate");
    include_once(getRoot() . "/controllers/Inmuebles/Controller/inmuebles.class.php");
    include_once(getRoot() . "/controllers/Formularios/Controller/formularios.class.php");
    $inmueblesController = new Inmuebles($this->db);
    $buscador=$inmueblesController->getFormulario("Buscador");
?>
<body>
    <?php include_once("header.php"); ?>
    <div class="buscadorFlotante" style="display:none;">
        <?php Formularios::printFormulario($buscador,$this->getDB()); ?>
    </div>
    <main>
        <div id="contactoAlia">
            <h2>Asóciate con Alia</h2>
            <p>Rellena el siguiente formulario y nos pondremos en contacto contigo lo antes posible</p>
            <div class="formContainer">
                <?php Formularios::printFormulario($asociate,$this->getDB()); ?>
            </div>
        </div>
    </main>
    <?php include_once("footer.php"); ?>
</body>
