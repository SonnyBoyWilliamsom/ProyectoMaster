<?php 

class Config{
	#Con esta clase lo único que hacemos es manejar los datos de configuración de nuestro proyecto. 
	#Contiene un metodo que permite el acceso a los valores instanciados en init.php (get($path)). 
	#Para poder usar esos valores globales se llamara a dicho método (estatico ya que no depende de los atributos de la clase sino del parametro de entrada $path)

	 //Todo metodo estatico se accede con los dobles puntos (::). Esto sustituye a la flecha (->) usada en metodos no estaticos
	public static function get($path = null){ //Esta funcion nos dejara acceder a la clase Config.php y a todos sus atributos en los que se encuentra la informacion para la conexion, sesion y cookies
	 	if($path){ //El acceso a los atributos se hace como si de un acceso a un directorio se tratase (ej. floder1/folder2)
	 		$config = $GLOBALS['config']; //En esta variable se encuantra toda la información de configuración del proyecto
	 		//echo $path;
	 		$path = explode('/',$path);
	 		//var_dump($path);
	 		foreach ($path as $crumb) {
	 			if(isset($config[$crumb])){
	 				$config = $config[$crumb];
	 			}else{
	 				return false;
	 			}
	 		}
	 		return $config;
	 	}
	 	return false;
	 }
}