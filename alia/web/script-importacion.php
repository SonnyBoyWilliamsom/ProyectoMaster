<?php
	ini_set("display_errors",1);
	include_once("processing/library.php");
	include_once("classes/inmuebles.php");
?>
<!DOCTYPE>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <title>Nuevo Milenio - Inicio</title>
    </head>
    <body>
    	<?php
        	$inmuebles=new Inmuebles(connectBD());
			$arrayInmueblesOld=$inmuebles->obtenerInmuebles();
			$db=mysqli_connect("localhost","root","root","milenio_new","8889");
			foreach($arrayInmueblesOld as $inmueble){
				$query="insert into inmuebles (referencia,c_tipinm,c_subtipinm,precio_compra,precio_alquiler,precio_traspaso,precio_alquiler_opcion_compra,
				informacion_gestion,c_pais,c_provin,c_pobla,zona,cp,posicion_exacta_01,	longitud,latitud,direccion_visible_01,direccion_completa_01,direccion_tipo_via,
				direccion_calle,direccion_numero,direccion_piso,direccion_letra,direccion_escalera,n_habitaciones,n_alcobas,n_banos,n_aseos,m2_utiles,m2_construidos,
				m2_terreno,estado_vivienda,accesible_01,amueblado_01,garaje_01,calefaccion_01,aire_acondicionado_01,piscina_01,jardin_01,trastero_01,ascensor_01,
				terraza_01,piso_banco_01,vpo_01,reservado_01,exclusividad_01,fecha_captacion,fecha_fin_mandato,referencia_catastral,idufir,rc_tomo,rc_libro,rc_folio,
				rc_finca,rc_registro,eficiencia_energetica_tipo,eficiencia_energetica_fecvalid,	eficiencia_energetica_emisiones,eficiencia_energetica_energia,
				eficiencia_energetica_documento_01,eficiencia_energetica_documento_visible_01,descripcion_publica,descripcion_publica_e,descripcion_publica_ger,
				descripcion_publica_cat,descripcion_publica_eus,visible,reservado,vendido,id_agente,gestion) values(\"" .$inmueble['referencia']. "\",0,0,".$inmueble['precio'].",
				".$inmueble['precio'].",".$inmueble['precio'].",".$inmueble['precio'].",\"".utf8_encode($inmueble['notas'])."\",0,0,0,\"\",\"\",0,\"\",\"\",0,0,\"\",
				\"\",\"\",\"\",\"\",\"\",0,".(int)$inmueble['dormitorios'].",".(int)$inmueble['banos'].",".(int)$inmueble['aseos'].",".(int)$inmueble['superficie_util'].",
				".(int)$inmueble['superficie_construida'].",".(int)$inmueble['superficie_solar'].",-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,0,0,0,0,'2014-01-01','2014-01-01',\"\",\"\"
				,\"\",\"\",\"\",\"\",\"\",\"".$inmueble['energia']."\",\"2014-01-01\",0,0,0,0,\"".str_replace("\"","'",strip_tags(utf8_encode($inmueble['observaciones'])))."\",\"\",\"\",\"\",\"\",".$inmueble['visible'].",".($inmueble['reservado']%2).",".floor($inmueble['reservado']/2).",".$inmueble['id_agente'].",".$inmueble['gestion'].")";
				echo $query;	
				queryBD($query,$db);
			}
		?>
    </body>
</html>