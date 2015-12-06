<div id="buscar-nuevo">
    <input type="text" placeholder="Buscar..." onKeyUp="javascript:realizarBusquedaEnTabla('#tabla_<?php echo strtolower($this->getName()); ?>', this.value);"/>
    <a href="javascript:void(0);" onclick="javascript:desplegar('#form_<?php echo strtolower($this->getName()); ?>_Buscador_Avanzado');">Búsqueda Avanzada</a>
    <a href="javascript:void(0);" onclick="javascript:deshacerBusqueda('#form_<?php echo strtolower($this->getName()); ?>_Buscador_Avanzado','#tabla_<?php echo strtolower($this->getName()); ?>');">Deshacer búsqueda</a>
    <a href="javascript:void(0);" onclick="javascript:desplegar('#form_<?php echo $this->getName();?>_Nuevo');" id="nuevo">Nuevo</a>
</div>
<?php
    if(array_search("Formularios",get_declared_classes())!=-1):
        $buscadorAvanzado=$this->getFormulario("Buscador_Avanzado");
        $datosBuscador=$buscadorAvanzado['datos'];
?>

    <div id="buscar-avanzado" class="plegado">
        <?php Formularios::printFormulario($buscadorAvanzado); ?>
    </div>
<?php endif; ?>