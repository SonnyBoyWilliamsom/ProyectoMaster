<?php
    /*Inicialización*/
    include_once(getRoot()."/controllers/Tipos/Controller/tipos.class.php");
    include_once(getRoot()."/controllers/Empresas/Controller/empresas.class.php");
    include_once(getRoot()."/controllers/Gestiones/Controller/gestiones.class.php");
    include_once(getRoot()."/controllers/Zonas/Controller/zonas.class.php");
    $tipos=new Tipos($this->getDB());
    $tipos->cargar();
    $zonas=new Zonas($this->getDB());
    $zonas->cargar();
    $this->cargar();
    $empresas=new Empresas($this->getDB());
    $empresas->cargar();
    $referencia=$_GET['referencia'];
    $ficha=$this->buscar('referencia',$referencia);
    if($ficha['activo_01']==1 || isset($_SESSION['usuario'])){
        $tipo=$tipos->buscar('id', $ficha['tipo']);
        $zona=$zonas->buscar('id',$ficha['zona']);
        $empresa=$empresas->buscar("id",$ficha['id_empresa']);
        $campos=$this->getLista("Ficha");
        $imagenes=$this->getFotos($ficha['c_inmuebles']);
        $pcpal=$this->getFotoPrincipal($ficha['c_inmuebles'],$this->getDB());
        $noPcpal=false;
        if(count($pcpal)==0){
            $pcpal['url']=getUrl()."/img/LogoAlia.png";
            $noPcpal=true;
        }
        $disponibilidades=array(0=>"Disponible",1=>"Reservado",2=>"Vendido");
        $campos=$this->getLista("Ficha");
        $textoReclamo=(strlen($ficha['texto_reclamo'])>0)?$ficha['texto_reclamo']:"Ampliamos información sin compromiso, ¡Llámanos!";
    }
    $buscador=$this->getFormulario("Buscador");

?>
<body>
    <script>
        $(document).ready(function(){
            $(".miniaturas li a").click(function(e){
                var miniatura=$(e.currentTarget);
                if(!miniatura.hasClass("active")){
                    var imagen=miniatura.css("background-image");
                    $(".foto").css("background-image",imagen);
                    $(".miniaturas li a.active").removeClass("active");
                    miniatura.addClass("active");
                }
            });
            <?php if(!$noPcpal): ?>
            $(".foto a").click(function(e){
                var foto=$(e.currentTarget);
                var imagen=foto.parent().css("background-image");
                var lighbox=$(".lightbox");
                lighbox.css("background-image",imagen);
                lighbox.show();
                $("html,body").css("overflow","hidden");
            });
            <?php endif; ?>
            $(".lightbox .cerrar").click(function(e){
                var lighbox=$(".lightbox");
                lighbox.hide();
                $("html,body").css("overflow","");
            });

            $(".lightbox").click(function(e){
                if(e.target==e.currentTarget){
                    var lighbox=$(".lightbox");
                    lighbox.hide();
                    $("html,body").css("overflow","");
                }

            });
            $(".lightbox .izquierda,.lightbox .derecha").click(function(e){
                e.preventDefault;
                e.stopPropagation;
                var elemento=$(e.currentTarget);
                var fotoActiva=$(".miniaturas li .active");
                var fotoSiguiente;
                var imagen;
                if(elemento.hasClass("izquierda"))
                    fotoSiguiente=fotoActiva.parent().prev();
                else
                    fotoSiguiente=fotoActiva.parent().next();
                if(fotoSiguiente.length>0){
                    imagen=fotoSiguiente.children("a").css("background-image");
                    fotoActiva.removeClass("active");
                    fotoSiguiente.children("a").addClass("active");
                    $(".foto").css("background-image",imagen);
                    $(".foto a").click();
                }
            });
        });
    </script>
    <?php include_once("header.php"); ?>
    <div class="buscadorFlotante" style="display:none;">
        <?php Formularios::printFormulario($buscador,$this->getDB()); ?>
    </div>
    <main>
        <div class="lightbox" style="display:none;">
            <a href="javascript:void(0);" class="cerrar"><i class="icon-cancel-circled"></i></a>
            <div class="navegacion">
                <a href="javascript:void(0);" class="izquierda"><i class="icon-left-open"></i></a>
                <a href="javascript:void(0);" class="derecha"><i class="icon-right-open"></i></a>
            </div>
        </div>
        <?php if($ficha['activo_01']==1 || isset($_SESSION['usuario'])) : ?>
        <div id="ficha">
            <div class="izquierda">
                <div class="empresa <?php echo $empresa['codigo_empresa'] ?>">
                    <img class="logo" src="<?php echo $empresa['logo'] ?>" alt="<?php echo $empresa['nombre'] ?>" />
                    <p class="nombre"><?php echo $empresa['nombre'] ?></p>
                    <p class="telefono"><?php echo $empresa['telefono'] ?></p>
                </div>
                <div class="datos">
                    <p class="tipo-precio"><?php echo $tipo['nombre']; ?><span class="derecha"><?php echo number_format($ficha['precio_compra'],0,",","."); ?>&euro;</span></p>
                    <p class="zona-ref">Alcorcón - <?php echo $zona['nombre']; ?><span class="derecha">Ref. <?php echo $ficha['referencia']; ?></span></p>
                    <p class="disponibilidad <?php echo strtolower($disponibilidades[$ficha['estado_gestion']]); ?>"><?php echo $disponibilidades[$ficha['estado_gestion']]; ?></p>
                    <p class="texto-reclamo"><?php echo $textoReclamo; ?></p>
                    <p class="titulo">Descripción</p>
                    <p class="descripcion"><?php echo $ficha['descripcion']; ?></p>
                    <p class="titulo">Detalles del inmueble</p>
                    <div class="detalles">
                        <?php for($i=0; $i<count($campos['cells']);$i++):
                            $label=$campos['cells'][$i]['data']['label_es'];
                            $key=$campos['cells'][$i][0]['key_bd'];
                            $value=$this->translateValues($key,$ficha[$key]);
                            if($value==null || $value==-1) continue;
                        ?>
                        <p class="dato">
                            <strong><?php echo $label; ?>:</strong>
                            <span><?php echo $value; ?></span></p>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <div class="derecha">
                <div class="foto" style="background-image:url('<?php echo $pcpal['url']; ?>');">
                    <a href="javascript:void(0);" title="Ver más grande"></a>
                </div>
                <ul class="miniaturas">
                    <?php for($i=0;$i<count($imagenes);$i++) :?>
                        <li><a href="javascript:void(0);" <?php if($imagenes[$i]['principal']==1) echo "class=\"active\" "; ?>style="background-image:url('<?php echo $imagenes[$i]['url']; ?>');"></a></li>
                    <?php endfor; ?>
                </ul>
                <div class="opciones">
                    <a class="mas-info" href="javascript:void(0);" onclick="$('#mas-info').submit();" title="Más Información"><i class="fa fa-plus-square"></i>Más Info</a>
                    <form style="display:none;" action="/contacto.php" id="mas-info" method="post">
                        <input type="hidden" value="<?php echo $ficha['referencia']; ?>" name="ref" />
                    </form>
                    <a class="imprimir-pdf" target="_blank" href="<?php ejecutarHooks('printToPdf', $this->db,$ficha); ?>" title="Descargar en PDF"><i class="fa fa-file-pdf-o"></i>Imprimir en PDF</a>
                </div>
                <p class="texto-vinculante">Ficha informativa no vinculante contractualmente. El precio publicado NO incluye gastos de escrituración e inscripción, así como los impuestos que correspondan, que se abonarán según Ley. El precio publicado SI incluye los honorarios profesionales del anunciante.</p>
            </div>
        </div>
        <?php else : ?>
            <p id="no-ref">Lo sentimos, no existe ningún inmueble disponible con la referencia introducida.</p>
        <?php endif; ?>
    </main>
    <?php include_once("footer.php"); ?>
</body>
