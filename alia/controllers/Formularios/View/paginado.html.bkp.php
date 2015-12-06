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
    <?php
    $i = 0;
    foreach ($campos as $campo) {
        $eventos=(isset($campo['eventos']) && count($campo['eventos'])>0)?" ".implode(" ",$campo['eventos']):"";
        if ($i % 3 == 0)
            echo "<div class=\"linea\">";
        if ($campo['tipo'] != "hidden" && $campo['tipo'] != "oculto") {
            echo "<div class=\"lado\">";
            echo "<label>" . $campo['label_' . $datos['lang']] . "</label>";
        }
        switch ($campo['tipo']) {
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
        if ($campo['tipo'] != "hidden" && $campo['tipo'] != "oculto") {
            echo "</div>";
            if ($i++ % 3 == 2)
                echo "</div>";
        }
    }
    if ($i % 3 != 0)
        echo "</div>";
    ?>
    <?php if($pagina>1) : ?>
        <a href="javascript:void(0);" onclick="javascript:paginaAnterior(<?php echo ($pagina-1); ?>);">Anterior</a>
    <?php endif; ?>
    <?php if($pagina<$paginas) : ?>
        <a href="javascript:void(0);" onclick="javascript:paginaSiguiente(<?php echo ($pagina+1); ?>);">Siguiente</a>
    <?php
        endif;
        $pagina++;
    ?>
</div>