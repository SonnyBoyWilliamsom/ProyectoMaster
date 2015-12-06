<div id="buscar-nuevo">
    <input type="text" placeholder="Buscar..." onKeyUp="javascript:realizarBusquedaEnTabla('#tabla_<?php echo strtolower($this->getName()); ?>', this.value);"/>
    <a href="javascript:void(0);" onclick="javascript:desplegar('#form_<?php echo $this->getName();?>_Nuevo');" id="nuevo">Nuevo</a>
</div>