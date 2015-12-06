<?php
    include_once(getRoot() . "/controllers/Inmuebles/Controller/inmuebles.class.php");
    $inmueblesBuscarRef = new Inmuebles($this->db);
    $buscadorRef=$inmueblesBuscarRef->getFormulario("Referencia");
?>
<header>
    <div class='content'>
        <div class='contentHeader'>
            <div class='name_tlf'>
                <p>Alia · Asociación de inmobiliarias de Alcorcón</p>
            </div>
            <div class='search_header'>
                <a href='http://www.ayto-alcorcon.es' target="_blank">Conocer Alcorcón</a>
                <a href='javascript:void(0);' onclick="javascript:mostrarFormulario('.buscadorFlotante');" id="buscar">Buscar</a>
            </div>
            <div class='logo'><a href='<?php echo getUrl(); ?>'></a></div>
            <div class='slogan'><span>Aquí se vende</span><!--<span>Vender</span><span>Alquila</span>--></div>
            <div class='contacto'>
            <a href="/contacto.php">Contacto</a>
                <ul>
                    <li><a target="_blank" href='https://www.facebook.com/Asociación-Local-de-Inmobiliarias-de-Alcorcón-ALIA-337500774345/'><i class="demo-icon icon-facebook-official"></i></a></li>
                    <li><a target="_blank" href='https://twitter.com/alcorconalia'><i class="demo-icon icon-twitter"></i></a></li>
                </ul>
            </div>
            <div class='acceso'>
                <ul>
                    <li><a href='<?php echo getUrl(); ?>/asociados.php'>Asociados ·</a></li>
                    <li><a href='<?php echo getUrl(); ?>/asociate.php'>Asóciate ·</a></li>
                    <li><a href='<?php echo getUrl(); ?>/admin' target="_blank">Acceso</a></li>
                </ul>
            </div>
            <div class='reference'>
                <?php Formularios::printFormulario($buscadorRef,$inmueblesBuscarRef->getDB()); ?>
            </div>
        </div>
    </div>
    <div class='navigation'>
        <nav>
            <ul>
                <li><a href='pisos-en-alcorcon.php'>Alcorcón</a></li>
                <li><a href='pisos-en-alcorcon-centro.php'>Zona centro</a></li>
                <li><a href='pisos-parque-lisboa.php'>Parque de Lisboa - La paz</a></li>
                <li><a href='pisos-valderas-castillo.php'>Valderas-Castillos</a></li>
                <li><a href='pisos-en-retamas-prado.php'>Retamas - Prado</a></li>
                <li><a href='pisos-parque-oeste.php'>Parque Oeste - F.Cisneros</a></li>
                <li><a href='pisos-en-ondarreta.php'>Ondarreta</a></li>
                <li><a href='pisos-en-campodon.php'>Campodón</a></li>
                <li><a href='pisos-juzgados-hacienda.php'>Juzgados</a></li>
            </ul>
        </nav>
    </div>
</header>
