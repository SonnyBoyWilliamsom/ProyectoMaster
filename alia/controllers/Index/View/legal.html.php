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
    <main id="aviso-legal">
        <h2>Aviso legal y privacidad</h2>
        <p>En cumplimiento del artículo 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y Comercio Electrónico, a continuación se exponen nuestros datos:</p>
        <p>Denominación social: Asociación Local de Inmobiliarias de Alcorcón</p>
        <p>CIF: G-86427754</p>
        <p>Domicilio social: C/ Oslo nº5, local 8, 28922-Alcorcón (Madrid)</p>
        <p>E-mail: info@alcorconalia.com</p>
        <h2>Propiedad intelectual</h2>
        <p>El código fuente, las imágenes y gráficos, animaciones, software, textos, base de datos, así como las marcas, logos, informaciones y contenidos que se recogen en http://www.alcorconalia.com son propiedad de Asociación Local de Inmobiliarias de Alcorcón y están sujetos a los derechos de propiedad industrial e intelectual de la empresa. En ningún caso el acceso al sitio Web implica ningún tipo de renuncia, transmisión o cesión total o parcial de los mencionados derechos, ni confiere ningún derecho de utilización, alteración, explotación, reproducción, distribución o comunicación pública sobre los contenidos o bienes de propiedad industrial sin la previa y expresa autorización específicamente otorgada a tal efecto por parte de Asociación Local de Inmobiliarias de Alcorcón.</p>
        <p>El usuario, única y exclusivamente, puede utilizar el material que aparezca en este sitio Web para su uso personal, privado y no lucrativo, quedando prohibido su uso con fines comerciales o ilícitos.</p>
        <h2>Condiciones de utilización del sitio web</h2>
        <p>La utilización de la pagina Web www.alcorconalia.com implica la aceptación plena y sin reservas de todas y cada una de las disposiciones incluidas en este Aviso Legal. En consecuencia, el usuario debe leer atentamente el presente Aviso Legal.</p>
        <p>1.Asociación Local de Inmobiliarias de Alcorcón podrá modificar o actualizar, sin previo aviso, la información contenida en su sitio Web, así como su configuración, presentación y condiciones de acceso.</p>
        <p>2.Todas las informaciones, fotos y datos que aparecen en esta página Web no tienen valor contractual, pudiendo ser modificadas sin previo aviso conforme a la voluntad de los propietarios de los inmuebles.</p>
        <p>3.Asociación Local de Inmobiliarias de Alcorcón no garantiza la plena disponibilidad de acceso al sitio Web, en su contenido, ni que éste se encuentre perfectamente actualizado, aunque priorizará siempre una adecuada actualización y mantenimiento del mismo.</p>
        <p>4.Asociación Local de Inmobiliarias de Alcorcón garantiza que todos los contenidos y servicios que se ofrecen en www.alcorconalia.com respetan el principio de dignidad de la persona, el principio de no-discriminación por motivos de raza, sexo, religión, opinión, nacionalidad, discapacidad o cualquier otra circunstancia personal y social, así como el principio de protección de la juventud y de la infancia.</p>
        <p>5.Asociación Local de Inmobiliarias de Alcorcón se compromete a través de este medio a no realizar publicidad engañosa. No serán considerados como publicidad engañosa los errores tipográficos o numéricos que puedan encontrarse a lo largo del contenido de las distintas secciones de la Web, comprometiéndose Asociación Local de Inmobiliarias de Alcorcón a solucionarlos a la mayor brevedad posible.</p>
        <p>6.Asociación Local de Inmobiliarias de Alcorcón se compromete a NO REMITIR COMUNICACIONES COMERCIALES SIN IDENTIFICARLAS COMO TALES, conforme a lo dispuesto en la Ley 34/2002 de Servicios de la Sociedad de la Información y de comercio electrónico.</p>
        <p>7.Tanto el acceso a este Web como el uso que pueda hacerse de la información contenida en el mismo es de la exclusiva responsabilidad de quien lo realiza.</p>
        <p>8.Asociación Local de Inmobiliarias de Alcorcón no responderá de ninguna consecuencia, daño o perjuicio que pudieran derivarse de dicho acceso o uso de la información.</p>
        <p>9.Asociación Local de Inmobiliarias de Alcorcón no se hace responsable de los posibles errores de seguridad que se puedan producir ni de los posibles daños que puedan causarse al sistema informático del usuario (hardware y software), los ficheros o documentos almacenados en el mismo, como consecuencia de la presencia de virus en el ordenador del usuario utilizado para la conexión a los servicios y contenidos de la Web, de un mal funcionamiento del navegador o del uso de versiones no actualizadas del mismo.</p>
        <p>10.Asociación Local de Inmobiliarias de Alcorcón no utiliza “cookies” en su pagina, la navegación por el sitio Web es anónima, únicamente se registra información sobre el numero de visitas, horas, etc. en los ficheros históricos o logs con fines exclusivamente estadísticos.</p>
        <h2>Política de protección de datos</h2>
        <p>Mediante el presente Aviso Legal y de acuerdo con lo establecido en la Ley Orgánica 15/1999, de Protección de Datos de Carácter Personal, Asociación Local de Inmobiliarias de Alcorcón avisa a los usuarios de su política de protección de datos, para que estos determinen libre y voluntariamente si desean facilitar los datos personales que se le puedan requerir con ocasión de la suscripción o alta en algunos de los servicios ofrecidos. Salvo en los campos en que se indique lo contrario, las respuestas a las preguntas sobre datos personales son voluntarias.</p>
        <p>Los datos personales recogidos serán tratados de forma secreta y confidencial y su recogida tiene como finalidad la gestión y prestación de los servicios ofrecidos por la empresa en el sitio Web y en cada momento.</p>
        <p>El usuario acepta que los datos personales por él facilitados puedan ser objeto de tratamiento en ficheros automatizados titularidad y responsabilidad de Asociación Local de Inmobiliarias de Alcorcón Los datos recabados mediante el formulario de contacto o e-mail solo se utilizan para el fin solicitado y solo se envían informaciones relacionadas con la actividad o con la finalidad para la que fueron recabados. Estos datos no son incorporados a ningún fichero, ya que son eliminados una vez terminada la actividad para la que fueron recabados.</p>
        <p>El usuario puede ejercer sus derechos de acceso, rectificación, cancelación y oposición en la siguiente dirección:</p>
        <p>C/ Oslo nº5, local 8, 28922-Alcorcón (Madrid)</p>
        <p>E-mail:info@alcorconalia.com</p>
        <p>Los Usuarios garantizan y responden, en cualquier caso, de la veracidad, exactitud, vigencia y autenticidad de los Datos Personales facilitados.</p>
        <h2>Ley aplicable y fuero</h2>
        <p>Estas Condiciones Generales se rigen por la ley española. Para cualquier controversia que pudiera derivarse de la aplicación de los servicios o interpretación o aplicación de las Condiciones Generales, la Empresa y el usuario, con renuncia expresa a su fuero propio se someten al de los Juzgados y Tribunales de Madrid.
    </main>
    <?php include_once("footer.php"); ?>
</body>

