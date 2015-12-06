<?php
	include_once("../processing/library.php");
	class Agentes{
		private $agentes;
		private $db=null;
		
		function Agentes($db){
			$this->agentes=array();
			$this->db=$db;
			$this->agentes=queryBD("select * from agentes",$this->db);
		}
		function obtenerAgentes(){
			return $this->agentes;	
		}
		function buscarAgente($campo,$valor){
			foreach($this->agentes as $agente){
				if($agente[$campo]==$valor)
					return $agente;
			}	
		}
		function insertarAgente(){
				
		}
		function modificarAgente($identificador){
		
		}
		function eliminarAgente($identificador){
			queryBD("delete from agentes where id=$identificador");
		}
	}
?>