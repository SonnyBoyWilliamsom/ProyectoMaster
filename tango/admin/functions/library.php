<?php

function getRoot() {
	//Con esta funcion devolvemos la ruta a cualquier archivo desde su raiz. De esta manera se evitan problemas de direcciones y perdidas de reconocimineto de archivos
    return dirname(dirname(__FILE__))."/";
}

function mainRoot() {
	//Con esta funcion devolvemos la ruta a cualquier archivo desde su raiz. De esta manera se evitan problemas de direcciones y perdidas de reconocimineto de archivos
    return dirname(__FILE__)."/";
}


function getUrl() {
	//Esta funcion solo devuelve la URL del proyecto
    return "http://tango.com";
}