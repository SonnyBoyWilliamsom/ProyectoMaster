<?php
class DBcorePDO{

    private static $_instance=null; //Variable para controlar si ya se ha instanciado la clase DBcorePDO. Por defecto se establece como que no ha sido establecida

    private $_pdo, 
            $_query, 
            $_results, 
            $_error = false,
            $_count = 0;

    private function __construct(){ #Al establecer el constructor como privado, nunca podremos crear un objeto DBcorePDO fuera de la clase
        try{
            //Creacion del obejto PDO que nos conectará a la base de datos segun la configuración de init.php (que se llaman desde Config.php)
            $this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'),array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo 'Successful connection';
        }
        catch(Exception $e){
            die('Error: '.$e->getMessage());
        }
    }

   #Será solo por el metodo getInstance() por el que se puede conectar a la base de datos fuera de esta clase
    public static function getInstance(){
        if(!isset(self::$_instance)){
             self::$_instance = new DBcorePDO();   
        }
        return self::$_instance;
    }


/*
    public function getInverse($table, $fields=array() ){
        
        $sql = 'select * from '.$table.' where '.array_keys($fields)[0].' = ? and '.array_keys($fields)[1].' !=? ';
        $i=1;
        echo $sql;
        if($this->_query = $this->_pdo->prepare($sql)){
            foreach ($fields as $field => $val) {
                $this->_query->bindValue($i,$field);
                $i++;
            }
            if($this->_query->execute()){
                $this->_count = $this->_query->rowCount();
                //echo $this->_count;
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); //fetch crea un array en el que cada elemento es un objet
                    //var_dump($this->_results);
            }else{ //si no se ha podido ejecutar la query se establece el error a 1
                 $this->_error = true;
            }
            
        }
        return $this;
    }*/


    public function query($sql, $params = array(), $type){ //$sql es la consulta y $params los valores con los que se va a trabajar 
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)){ //Es el atributo privado $_query el que almacena la peticion sql. 
            $posVal=1;
            if(count($params)){ 
                foreach($params as $param){
                    $this->_query->bindValue($posVal,$param);
                    $posVal++;
                }
            }
            //Una vez hecha la asignacion de valores se ha de ejecutar la consulta con los valores ya asignados
            if($this->_query->execute()){ 
                $this->_count = $this->_query->rowCount();
                //echo $this->_count;
                if($type == null){
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); //fetch crea un array en el que cada elemento es un objet
                    //var_dump($this->_results);
                }
               
            }else{ //si no se ha podido ejecutar la query se establece el error a 1
                 $this->_error = true;
            }
        }
        return $this; //Se devuelve el objeto en el que nos encontramos (this es un objeto de clase DBcorePDO) ya que en el proceso puede haber sufrido cambios (errores, resultados ...)
    }

    //Para abstaer mas las cosultas y no hacer necesario escribir la cosulta cada vez que se necesitan datos se van a crear funciones que ya tengan la consulta preparada. Para ello se usara una funcion principal que se encargara de hacer cada consulta dependiendo de la funcion que la llame (action($action, $table, $where){). Esta es privada ya que solo se usara dentro de la clase DBcorePDO
    private function action($action, $table, $where, $type){ 
        //si se han establecido todos los parametros de $where (campo, operador y valor)
        if(count($where)==3 || $where == null){
            $single='';
            $value=''; //Si la funcion se llama sin where es porque se trata de una consulta global (todos los registros)
            if(count($where)==3){
                $operators = array('=','!=','<=','>=','<','>');
            
                $field = $where[0];
                $operator = $where[1];
                $value = $where[2];
                
            // Si se llama a la funcion con un operador permitido
                if(in_array($operator, $operators)){
                //preparamos la consulta:
                $single=" WHERE {$field} {$operator} ?";
                //Creamos la _query   
                }
            }
            $sql = "{$action} FROM {$table}".$single; 
            //echo $sql;
            if(!$this->query($sql, array($value), $type)->getError()){ //Con getError de la query() comprobamos si no hay error
                    //Si no hay error devolvemos el objeto
                    return $this; 
                } 
        }
        return false; //En caso de que no se haya podido hacer la query es porque algo ha ido mal, luego devolvemos false
    }

    public function insert($table, $fieldsVals=array(),$type=1){
        if(count($fieldsVals)>0){
            $values = '';
            foreach ($fieldsVals as $param) { //Valores ocultos para la cosulta preparada
                $values .= '?,';
            }
           
            $values = substr($values, 0, strlen($values)-1); //Eliminacion de la coma final 
            $fields = array_keys($fieldsVals);//Creacion de los campos que se van a insertar
            $fields = implode(', ',$fields);
            $sql = "insert into {$table} ({$fields}) values ({$values})";
            //die($fieldsVals);
            //echo $sql;
            //var_dump($fieldsVals);
            if(!$this->query($sql, $fieldsVals, $type)->getError()){
                return true;
            }
        }
        return false;
    }

    public function delete($table, $where, $type=2){
         $action='delete';
        return $this->action($action, $table, $where, $type);
    }

    public function update($table, $condVals=array(), $fieldsVals=array(),$type=3){
        if(count($fieldsVals)>0){
            $values='';
            $conditions='';
            $fields = array_keys($fieldsVals);
            foreach($fields as $field){
                $values []= $field .' =?';
            }
            $values = implode(', ', $values);
            $conds = array_keys($condVals);
            foreach($conds as $cond){
                $conditions []= $cond .' =?';
            }
            $conditions = implode(' and ' , $conditions);
            
        //var_dump($values);
        $paramsOut = explode(',',implode(',', $fieldsVals).','.implode(',',$condVals));
         $sql="update {$table} set {$values} where {$conditions}";
            //echo $sql;
          if(!$this->query($sql, $paramsOut, $type)->getError()){
                return true;
            }
        }
       return false;
    }
    public function getError(){
        return $this->_error;
    }

    public function getResults(){
        return $this->_results;
    }

    public function getAll($table, $where = null, $type=null){
        $action = 'select *';
        return $this->action($action, $table, $where, $type);
    }

    public function get($table, $where, $type=null){
        $action='select *';
        return $this->action($action, $table, $where, $type);

    }

    public function count(){
        return $this->_count;
    }

    
    
}