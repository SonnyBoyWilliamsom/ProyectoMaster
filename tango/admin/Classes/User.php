<?php
//clase encapsulada = clases con atributos privados y funciones públicas, con la finalidad de impedir el acceso directo a los atributos, evitando así un valor inapropiado para ellos.
class User{
    //atibutos privados -> corresponden a cada una de las columnas de la bbdd de esa tabla que representa la clase
    private $_db,
            $_data;

    public $isLogged=false;

    //La clase ususario permite, una vez se ha creado un objeto de sicha clase, crear un nuevo usuario, encontrar un usuario, logear a un usuario, etc
    //El construtor crea la conexion con la base de datos. Si no se le pasa parametro de entrada, la clase comprueba si hay session con el ID de usuario.
    //Se puede saber si un usuario esta logeado tan solo llamando al metodo isLoggedIn() 
    //Si al cconstructor se le pasa un parametro de entrada (en este sistema el ID) 

    public function __construct($user = null){
        $this->_db = DBcorePDO::getInstance();
        //En este constructor de usuario podemos pasar un parametro de entrada, el usuario. De esta manera podemos obetenr la info de un usuario en contreto que no sea el actual (el que esta en session)
        //Si no le pasamos parametro de entrada (que es el id del usuario) se comprobara si hay sesion activa
        if(!$user){
            if(Session::exists($this->session())){ //Si se ha establecido un valor para $_SESSION[$name] podremos obtenerlo
            $userSession = Session::get($this->session()); //Obtencion del ID de usuario en este caso
                //echo 'No se le ha pasado al constructor de user un valor concreto luego el objeto va a comprobar si session tiene un valor determinado. Vamos a crear una variable para saber si un usuario esta logeado. Hacemos esta comprobacion en la clase usuario para no hacerla directamente en la pagina principal o de perfil, sino directamente llamar a dicha variable';
                if($this->findUser(array($this->session()=>$userSession))){ //Que exista la sesion con un valor para el ID no significa que el usuario exista, luego hay que comprobar esa posibilida
                    $this->isLogged = true;
                }
            }
        }else{
            $this->findUser(array($this->session()=>$user));
        }
    }

    public function createUser($fields = array()){
        //En la clase User.php 
        if(!$this->_db->insert('users', $fields)){

            return false;
        }

        return true;
    }

    public function findUser($field){ // Ejemplo de llamada: loginUser(array('email'=>Input::get('email')))
        //El metodo obtiene la clave para buscar el usuario
        $key = array_keys($field)[0];
        //Despues se obtiene el valor que ha de tener dicha clave.
        $keyVal = $field[$key];
        //Se accede a la base de datos para ver si el usuario (ya sea por nombre de usuario o por email) existe
        $reg = $this->_db->get('users',array($key,'=',$keyVal));
        
        if($reg->count()>0){
            $this->_data = $reg->getResults()[0];
            return $this;
        }
        return false;
    }

    public function update($cond=array(),$fieldVals=array()){
        if(!$this->_db->update('users', $cond, $fieldVals)){

            return false;
        }

        return true;
    }

    public function loginUser($field=null, $pass=null, $remember=null){

        //Si el usuario ha pedido no cerrar sesion (Remember me) hay que establecer la $_SESSION['id_user'] para logearlo directamente
        //Para ello se llama a este metodo sin parametros de entrada
        if(!$field && !$pass && !empty($this->data())){
            //Cuando se llama al constructor de User con parametro de entrada, este devuelve el objeto perteneciente a esta clase. 
            //Lo que hace el contructor es llamar al metodo findUser(id_user) y devuelve el objeto (y sus datos $this->data()) de ese identificador
            Session::put($this->session(), $this->data()->id_user);

        }else{
            if($this->findUser($field)){
                if($this->data()->password === Hash::make($pass, $this->data()->salt)){
                    //Session::flash('login', 'Login Correct! Welcome to Tango!');
                    Session::put($this->session(), $this->data()->id_user); 
                    //Si el usuario quiere ser recordado esablecemos las cookies
                    //Para establecer una cookie es necesario crear una tabla en nuestra BDD que alberque la cookie creada.
                    //De esta manera se puede comprobar si una cookie es valida para el acceso ya que va asociada al id de usuario
                    //
                    if($remember){
                        //El valor de la cookie los establecemos como un hash
                        $hashCookie = Hash::unique(); 
                        //Comprobamos que el usuario con el que estamos trabajando no tiene asociada ya una cookie (hash en la base de datos)
                        $userCheck = $this->_db->get('users_session', array('user_id','=',$this->data()->id_user));
                        #    echo $hashCookie;
                        //En caso de que no exista usuario con cookie asociada en la BDD se crea
                        if(!$userCheck->count()){
                             $this->_db->insert('users_session',array(
                                'user_id'=>$this->data()->id_user,
                                'hash'=>$hashCookie
                            ));
                        }else{
                            //Si el id de usuario ya tenia una cookie asociada en la BDD el valor de la cookie (hash) es el que ya tenia
                            //De esta manera al guardar en nuestra base de datos el usuario y la cookie podremos reconocer al usuario y su cookie
                            $hashCookie = $userCheck->getResults()[0]->hash; //results()[0] hace referencia al primer (y unico) elemento del get
                        }

                        //Una vez hemos establecido esos valores en nuestra BDD (si no existian ya) lo almacenamos en $_COOKIE['hash'] ('hash' es el valor de Config::get('remember/cookie_name'))
                        //Se establece la cookie con ese valor que a la vez se almacena en la BDD

                        Cookie::put($this->cookieUser(), $hashCookie,$this->cookieExpire());
                            //Si la cookie se ha establecido sin problemas la almacenamos en la base de datos, en la tabla users_session
                           
                        
                    }
                    return true;
                }
            }
        }
        return false; 
    }

    public function getAllUsers($users = 'users'){
        $this->_db->getAll($users);
        $aux = array();
        //Con este array se construye la siguiente estructura de array:
        /*
                array(
                        array('usuario'=>objetoUsuario),
                        array('usuario'=>objetoUsuario),
                        array('usuario'=>objetoUsuario),
                        ...
                );
        */
        foreach ($this->_db->getResults() as  $user) {
                    $aux []= array('user'=>$user);
        }
        return $aux;
    }

    public function data(){
        return $this->_data;
    }

    public function session(){
        return Config::get('session/session_name');
    }

    public function cookieUser(){
        return Config::get('remember/cookie_name');
    }

    public function cookieExpire(){
        return Config::get('remember/cookie_expire');
    }

    public function isLoggedIn(){
        return $this->isLogged;
    }

    public function logout(){ //Este metodo elimina la sesion, la cookie de usuario y el hash(BDD) que se asocia a la cookie y al usuario que la genero

        Session::delete($this->session());
        Cookie::delete($this->cookieUser());
        $idDelete = $this->_data->id_user;
       
        $this->_db->delete('users_session', array('user_id','=',$idDelete));
    }



/*
    private $id_user, $name, $surname, $email, $site, $field, $date_register, $password;
    
    //constructor = función que será invocada automaticamente al instanciar el objeto, dando valor a sus atributos
    function __construct($id_user, $name, $surname, $email, $site, $field, $date_register, $password){
        $this->id_user = $id_user;
        $this->name = $name;
        $hits->surname = $surname;
        $this->email = $email;
        $this->site = $site;
        $this->date_registred = $date_registred;
        $this->password = $password;
    }
*/
    //Funciones GET/SET = funciones que realizan operaciones de lectura y escritura sobre los atributos (obtenedor/establecedor)
    /*function getId(){
        return $this->id_user;
    }

    function getEmail(){
        return $this->email;
    }

    function getName(){
        return $this->name;
    }

    function getSurname(){
        return $this->surname;
    }

    function getSite(){
        return $this->site;
    }

    function getDate(){
        return $this->date_registred;
    }

    function getPass(){
        return $this->password;
    }*/
    
}