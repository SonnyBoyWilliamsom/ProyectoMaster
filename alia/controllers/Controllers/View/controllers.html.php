<?php
    include_once(getRoot()."/controllers/Base/View/base_admin.html.php");
    $this->cargar();
    $campos=$this->getFormulario();
    unset($campos['datos']);
?>
<body>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <input type="text" placeholder="Buscar..." onKeyUp="javascript:realizarBusquedaEnTabla('#tabla_controllers',this.value);"/>
        <select onChange="javascript:elegirFormato('#exportar',this.value);">
            <option value="xml" selected>XML</option>
            <option value="csv">CSV</option>
        </select>
        <a href="exportar.php?controller=<?php echo $this->getName() ?>&format=xml" id="exportar" target="_blank">Exportar</a>
	<form action="importar.php" method="post" target="_blank" enctype="multipart/form-data">
            <input type="hidden" name="controller" value="<?php echo $this->getName() ?>" />
            <input type="file" name="archivo" />
            <input type="submit" name="submit" value="Importar"/>
        </form>
        <table cellspacing="0" id="tabla_controllers">
            <tr>
                <th><a href="javascript:void(0)" onClick="javascript:eliminarVarios('<?php echo $this->getName(); ?>',<?php echo $this->getAjaxIndex("delete_selected"); ?>,'#tabla_controllers');">Eliminar Seleccionados</a></th>
                <?php
                    foreach($campos as $campo){
                        if($campo['tipo']=="password") continue;
                        echo "<th>". $campo['label_es'] ."</th>";
                    }
		?>
		<th>Acci&oacute;n</th>
            </tr>
            <?php
                foreach($this->obtenerRegistros() as $registro){
                    echo "<tr id=\"". $registro['id'] ."\">
                        <td><input type=\"checkbox\" class=\"". $registro['id'] ."\"></td>";
			foreach($campos as $campo){
                            if($campo['tipo']=="password") continue;
                            echo "<td>". $registro[$campo['key_bd']] ."</td>";	
			}
			echo "<td>";
                            echo "<ul>
                                    <li><a href=\"\" >modificar</a></li>
                                    <li><a href=\"javascript:void(0);\" onclick=\"javascript:eliminar('". $this->getName() ."',". $this->getAjaxIndex("delete") .",". $registro['id'] .",$('#".$registro['id']."'));\">eliminar</a></li>
                                </ul>
			</td>
                    </tr>";
		}
            ?>
        </table>
    </div>
</body>