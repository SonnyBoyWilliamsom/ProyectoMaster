<?php
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
    <main id="empresas-asociadas">
        <h2>Asociación de inmobiliarias de Alcorcón</h2>
        <p>Alcorconalia.com es la página Web de la Asociación Local de Inmobiliarias de Alcorcón (ALÍA), formada por un grupo de empresas inmobiliarias expertas en intermediación de reconocida y amplia experiencia en Alcorcón (Madrid). Su misión es facilitar una operativa de colaboración entre las oficinas y agentes que la componen, y regular la gestión de propiedades de forma compartida. Al contratar la gestión de venta a una empresa perteneciente a ALÍA, su inmueble está siendo promocionado simultáneamente por todas las empresas que la componen, en el mismo precio y condiciones, multiplicando las probabilidades de encontrar un comprador, y de hacerlo en menos tiempo. En cualquiera de las oficinas pertenecientes a ALÍA, usted accederá a una gran oferta de inmuebles de la manera más rápida, cómoda y fácil. ALÍA establece un estándar de calidad, al observar las más estrictas normas de transparencia y profesionalidad, de acuerdo a su propio <a href="<?php echo getUrl(); ?>/pdf/CodigoDeontologico.pdf" target="_blank" title="Código Deontológico">Código Deontológico Profesional de ALÍA.</a></p>
        <p>Nuestra Web es dinámica, moderna y muy usable, así como excelentemente posicionada en nuestro ámbito local de actuación, el municipio de Alcorcón, para que cualquier comprador o potencial inquilino de piso, chalet, local, etc en Alcorcón, lo tenga sumamente fácil. Todas las propiedades insertadas mantienen los mismos precios y condiciones, independientemente de la empresa con la que se trabaje, lo que supone una mayor transparencia del mercado inmobiliario que favorece siempre al consumidor. Las oportunidades de encontrar el producto que satisfaga sus necesidades se multiplican considerablemente con la visita a una sola oficina o mediante nuestra página Web.</p>
        <p>Un servicio eficaz, rápido y seguro. Control y seguridad en las visitas a su propiedad donde un asesor personal realizará un completo y eficaz seguimiento periódico de sus demandas. Control y seguridad para el comprador ya que siempre existe un encargo o contrato de mediación por escrito y en vigor para cada inmueble que describe las condiciones actualizadas relevantes para cada operación, poniendo a su disposición toda la información administrativa y jurídica necesaria de cada inmueble.</p>
        <h2>Bienvenido a nuestra web</h2>
        <p>En septiembre de 2012, ALÍA está formada por 25 profesionales inmobiliarios, 8 firmas locales y 8 puntos de atención al cliente en Alcorcón, creciendo día a día y aportando auténtico valor añadido profesional para su seguridad y tranquilidad. Asimismo disponemos de acuerdos de colaboración con diferentes asociaciones inmobiliarias, como por ejemplo la <a href="http://www.ainmo.es" title="AINMO" target="_blank">Asociación Inmobiliaria de Móstoles (AINMO).</a></p>
        <h2>Presentación de la asociación</h2>
        <p>El pasado 14 de junio de 2012, la Asociación Inmobiliaria de Alcorcón, realizó una presentación oficial en el IMEPE de Alcorcón informando acerca de las ventajas de formar parte de la asociación y animando a otras empresas del sector inmobiliario a formar parte de ésta. Agradecemos al Ayuntamiento de Alcorcón su apoyo a esta iniciativa asociativa profesional.</p>
    </main>
    <?php include_once("footer.php"); ?>
</body>

