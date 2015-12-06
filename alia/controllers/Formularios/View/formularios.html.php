<?php
    include_once(getRoot()."/controllers/Base/View/base_admin.html.php");
    $this->cargar();
    $ids_formularios=$this->ids_formularios();
    $tipos=$this->getTipos();
    $idiomas=new Idiomas($this->db);
    $idiomas->cargar();
    $iso=$idiomas->obtenerIso();
    
?>
<body>
    <script>
        $(document).ready(function(){
            $("td.editable").click(editar);
        });
    </script>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <section id="formularios">
            <?php
                foreach($ids_formularios as $id){
                    $campos=$this::getFormulario("es", $id['id_formulario'],$this->db);
                    echo "<p>". Formularios::getControllerName($id['id_formulario'],$this->db)."->".$campos['datos']['name']."</p>";
                    unset($campos['datos']);
                    echo "<table id=\"".$id['id_formulario']."\">";
                    echo "<tr><th>Key en BD</th><th>Tipo</th>";
                    foreach($iso as $codigo)
                        echo "<th>Nombre del campo (".$codigo.")</th>";
                    echo "<th>Longitud Aproximada</th><th>Obligatorio</th><th>Orden</th><th>Eliminar</th></tr>";
                    foreach($campos as $campo){
                        if($campo['tipo']=="oculto") continue;
                        echo "<tr class=\"" . $campo['id_campo'] . "\">";
                            echo "<td class=\"editable key_bd\">" . $campo['key_bd'] . "</td>";
                            echo "<td class=\"tipo\"><select onchange=\"javascript:modificarSelect($(this));\">";
                            foreach($tipos as $tipo){
                                echo "<option value=\"".$tipo['valor']."\"";
                                if($tipo['valor']==$campo['tipo']) echo " selected";
                                echo ">" . $tipo['opcion'] . "</option>";
                            }
                            echo "</select></td>";
                            foreach($iso as $codigo)
                                echo "<td class=\"editable label_$codigo\">" . $campo['label_' .$codigo] . "</td>";
                            echo "<td class=\"editable longitud\">".$campo['longitud']."</td>";
                            echo "<td class=\"obligatorio\"><input type=\"checkbox\" onclick=\"javascript:modificarChk($(this));\" ";
                            if($campo['obligatorio']) echo " checked";
                            echo "></td>";
                            echo "<td class=\"editable orden\">".$campo['orden']."</td>";
                            echo "<td class=\"eliminar\" ><a href=\"javascript:void(0);\" onclick=\"javascript:eliminar('".$this->getName()."',".$this->getAjaxIndex('delete').",'" . $campo['id_campo'] . "',$('tr." . $campo['id_campo'] . "'))\">Eliminar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<a href=\"javascript:void(0);\" onclick=\"javascript:cambiarFormulario('".$id['id_formulario']."');\">Aplicar cambios</a>";
                    echo "<a href=\"javascript:void(0);\" onclick=\"javascript:agregarCampo('".$id['id_formulario']."');\">Agregar campo</a>";
                    echo "<hr>";
                }
            ?>
        </section>
    </div>
</body>