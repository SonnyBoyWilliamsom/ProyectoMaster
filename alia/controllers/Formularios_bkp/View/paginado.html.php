<?php $titulos=array(1=>"Datos de localización y gestión",2=>"Datos de gestión",3=>"Datos característicos detallados del inmueble"); ?>
<?php if (!isset($pagina)) $pagina = 1; ?>
<?php if($pagina==1) : ?>
    <script>
        function paginaSiguiente(pagina){
            $(".paginado.activo").removeClass("activo");
            $(".paginado."+pagina).addClass("activo");
        }
        function paginaAnterior(pagina){
            $(".paginado.activo").removeClass("activo");
            $(".paginado."+pagina).addClass("activo");
        }
    </script>
<?php endif; ?>
<div class="paginado tres-columnas <?php echo $pagina; ?> <?php if ($pagina==1) echo "activo"; ?>">
    <p class="titulo-pagina"><?php echo $titulos[$pagina]; ?></p>
    <div class="campos">
        <?php
        $i = 0;
        foreach ($campos as $campo) {
            $eventos=(isset($campo['eventos']) && count($campo['eventos'])>0)?" ".implode(" ",$campo['eventos']):"";
            if($i==0) echo "<div class=\"linea\">";
            if ($campo['tipo'] != "hidden" && $campo['tipo'] != "oculto"){
                echo "<div class=\"lado";
                if($i==1) echo " central";
                echo"\">";
                echo "<label>" . $campo['label_' . $datos['lang']] . "</label>";
            }
            switch ($campo['tipo']){
                case "select":
                    echo "<select type=\"" . $campo['tipo'] . "\" name=\"" . $campo['key_bd'] . "\" class=\"" . $campo['key_bd'] . "\"";
                    if ($campo['obligatorio'])
                        echo "class=\"required\" ";
                    echo" $eventos>";
                    if (isset($campo['opciones'])) {
                        foreach ($campo['opciones'] as $opcion) {
                            echo "<option value=\"" . $opcion['valor'] . "\">" . $opcion['opcion'] . "</option>";
                        }
                    }
                    echo "</select>";
                    break;
                case "textarea":
                    echo "<textarea name=\"" . $campo['key_bd'] . "\" class=\"" . $campo['key_bd'] . "\" placeholder=\"" . strip_tags($campo['label_' . $datos['lang']]) . "\" $eventos></textarea>";
                    break;
                case "hidden":
                    echo "<input type=\"" . $campo['tipo'] . "\" name=\"" . $campo['key_bd'] . "\" class=\"" . $campo['key_bd'] . "\" $eventos>";
                    break;
                case "radio":
                    print_r($campo);
                    if ($campo['selected'] == true)
                        $selected = "checked";
                    else
                        $selected = "";
                    echo "<input type=\"" . $campo['tipo'] . "\" name=\"" . $campo['key_bd'] . "\" class=\"" . $campo['key_bd'] . "\" value=\"" . $campo['value'] . "\" $selected $eventos>";
                    break;
                case "oculto": break;
                default:
                    echo "<input type=\"" . $campo['tipo'] . "\" name=\"" . $campo['key_bd'] . "\" class=\"" . $campo['key_bd'] . "\"  placeholder=\"" . strip_tags($campo['label_' . $datos['lang']]) . "\" ";
                    if ($campo['obligatorio'])
                        echo "class=\"required\" ";
                    echo" $eventos>";
                    break;
            }
            if ($campo['tipo'] != "hidden" && $campo['tipo'] != "oculto") echo "</div>";
            if($i==3){
                echo "</div>";
                $i=0;
            }
            else $i++;
        }
        if($i!=0) echo "</div>";
        ?>
    </div>
    <div class="direccion">
        <?php if($pagina>1) : ?>
            <a href="javascript:void(0);" class="anterior" onclick="javascript:paginaAnterior(<?php echo ($pagina-1); ?>);"><span class="fa-angle-double-left"></span>Anterior</a>
        <?php endif; ?>
        <?php if($pagina<$paginas) : ?>
            <a href="javascript:void(0);" class="siguiente" onclick="javascript:paginaSiguiente(<?php echo ($pagina+1); ?>);">Siguiente<span class="fa-angle-double-right"></span></a>
        <?php
            endif;
            $pagina++;
        ?>
    </div>
</div>