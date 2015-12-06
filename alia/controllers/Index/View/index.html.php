<?php
    include_once(getRoot() . "/controllers/Inmuebles/Controller/inmuebles.class.php");
    include_once(getRoot() . "/controllers/Empresas/Controller/empresas.class.php");
    $inmueblesController = new Inmuebles($this->db);
    $buscador=$inmueblesController->getFormulario("Buscador");
    $empresasController=new Empresas($this->db);
    $campos=$empresasController->getLista("Empresas");
    $empresasController->cargar();
    $empresas=$empresasController->obtenerRegistros();
    $codigoAsociado=Empresas::getAsociadoMes($this->getDB());
?>
<body>
    <script>
        var rtime;
        var timeout = false;
        var delta = 200;
        $(document).ready(function () {
            $("#sliderMain").slider({
                exclude: '#busquedaRapida',
                time: 5000,
                transition: 600,
                arrows: {selector: '#flechas-cursos', left: 'img/flechaLeft03.png', right: 'img/flechaRight03.png'}
            });
            $("#buscar").attr("onclick","");
            $("#buscar").click(function(){
                $("#busquedaRapida").css("color","white");
                var i=0;
                var intervalo=setInterval(function(){
                    var color=(i++%2==0)?"black":"white";
                    $("#busquedaRapida").css("color",color);
                    if(i==5) clearInterval(intervalo);
                },600);
            });
            centrarTitulos();
            centrarCajaSlider();
            $("#form").validate(true);
            $("#form2").validate(true);
        });
        $(window).resize(function () {
            centrarTitulos();
            rtime = new Date();
            if (timeout === false) {
                timeout = true;
                setTimeout(resizeend, delta);
            }
        });
        function mostrarFormularioSegundo() {
            $("#sombra").show();
            var form = $("#formulario-promocion");
            var ventana = $(window);
            var izquierda = (ventana.width() - form.width()) / 2;
            var arriba = (ventana.height() - form.height()) / 2;
            form.css({"top": arriba, "left": izquierda}).show();
        }
        function centrarResultadoSegundo() {
            var form = $("#resultado");
            var ventana = $(window);
            var izquierda = (ventana.width() - form.width()) / 2;
            var arriba = (ventana.height() - form.height()) / 2;
            form.css({"top": arriba, "left": izquierda}).show();
        }
        function ocultarFormularioSegundo() {
            $("#formulario-promocion,#sombra").hide();
        }
        function mostrarFormularioTercero() {
            $("#sombra").show();
            var form = $("#formulario-promocion2");
            var ventana = $(window);
            var izquierda = (ventana.width() - form.width()) / 2;
            var arriba = (ventana.height() - form.height()) / 2;
            form.css({"top": arriba, "left": izquierda}).show();
        }
        function centrarResultadoTercero() {
            var form = $("#resultado");
            var ventana = $(window);
            var izquierda = (ventana.width() - form.width()) / 2;
            var arriba = (ventana.height() - form.height()) / 2;
            form.css({"top": arriba, "left": izquierda}).show();
        }
        function ocultarFormularioTercero() {
            $("#formulario-promocion2,#sombra").hide();
        }
    </script>
    <?php include_once("header.php"); ?>
    <main>
        <section id="sliderMain">
            <div class="slider segundo">
                <div class="auxiliar">
                    <div class="caja">

                    </div>
                </div>
                <img src="/img/slider-01.jpg" alt="Slider" />
            </div>
            <div class="slider segundo">
                <div class="auxiliar">
                    <div class="caja">

                    </div>
                </div>
                <img src="/img/slider-01.jpg" alt="Slider" />
            </div>
            <div class="slider segundo">
                <div class="auxiliar">
                    <div class="caja">

                    </div>
                </div>
                <img src="img/alia.jpg" alt="Slider" />
            </div>
            <div id="flechas-cursos"></div>
            <div id="busquedaRapida">
                <h2>Búsqueda Rápida</h2>
                <?php Formularios::printFormulario($buscador,$this->getDB()); ?>
            </div>
        </section>
        <div class='content'>
            <section id='asociados'>
                <div id='locateAsociados'></div>
                <p>Nuestros Asociados, la Garantía de Alia</p>
                <p>Alía es la Asociación Local de Inmobilbiaria de Alcorcón formada por profsionales con amplia experiencia en la gestión inmobiliaria. Los expertos de Alía, le asesoran y dan acceso de manera directa e instantánea a la más extensa, completa y actualizada base de datos de pisos en Alcorcón. Nuestra misión es satisfacer las necesidades de oferta y demanda mediante la colaboración eficaz, continua y activa entre profesionales bien formados.</p>
                <p>Asociación Local de Inmobiliarias de Alcorcón, entidad jurídica sin ánimo de lucro, inscrita en el Registro General de Asociaciones de la Consejería de Presidencia y Justicia de la Comunidad de Madrid con el nº: 33054. Abril 2012. C.I.F: G-86427754.</p>
                <div id='empresas'>
                    <div id="empresas">
                        <?php foreach($empresas as $empresa): ?>
                        <div class="empresaInfo">
                            <a href="http://<?php echo $empresa['web']; ?>" target="_blank">
                            <div class='infoLogo <?php echo $empresa['codigo_empresa']; ?>' style="background-image:url('<?php echo $empresa['logo']; ?>')"></div>
                            <p class='infoTlf'><?php echo $empresa['telefono']; ?></p>
                            <p class='infoAddress'><?php echo $empresa['web']; ?></p>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
        <section id='noticias'>
            <div class='content'>
                <p><a href="http://alcorconalia.blogspot.com.es/" target="_blank" title="Últimas noticias">Últimas noticias</a></p>
                <?php ejecutarHooks("printNews",$this->db);?>
            </div>
        </section>
        <section id='membership'>
            <div class='content'>
                <p><a href="/asociate.php" title="¿Quieres ser de Alía?">¿Quieres ser de Alía?</a></p>
                <p>Te lo ponemos fácil mira <a href="/asociate.php" title="¿Quieres ser de Alía?">aquí</a>.</p>
            </div>
        </section>
        <section id='asociadoMes' class="<?php echo $codigoAsociado; ?>">
            <p class="tituloAsociado">Asociado del mes</p>
            <?php Empresas::renderDestacado($codigoAsociado); ?>
        </section>
    </main>
    <?php include_once("footer.php"); ?>
</body>
