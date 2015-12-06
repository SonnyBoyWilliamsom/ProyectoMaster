<?php

class Validate{
	private $_passed = false,
			$_errors = array(),
			$_db = null;
	public function __construct(){
		$this->_db = DBcorePDO::getInstance(); //Siempre que necesite usar la base de datos desde una clase se ha de instanciar la clase que tiene la conexión y las funciones de consulta (DBcorePDO.php)
	}

	public function check($method, $itemsCond = array()){
		foreach($itemsCond as $item => $conds){
			foreach($conds as $cond => $val){
				$valForm = trim($method[$item]);
				
				if($cond == 'required' && $val == true && empty($method[$item])){

					$this->addError(ucfirst($item)." required.");
				}elseif(($cond == 'required' && $val == false) || $valForm!=''){
					switch ($cond){
						case 'min':
							if(strlen($valForm) < $val){
								$this->addError(ucfirst($item)." must to be at least {$val} caracters long.");
							}
						break;
						case 'max':
							if(strlen($valForm) > $val){
								$this->addError(ucfirst($item)." must to be maximum {$val} caracters long.");
							}
						break;
						case 'matches':
							if($valForm != trim($method[$val])) {
								$this->addError("Passwords do not match.");
							}
						break;
						case 'unique':
							if($this->_db->get($val, array($item,'=',$valForm))->count()>0) {
								$this->addError(ucfirst($item)." already exists.");
							}
						break;
						case 'uniqueInverse':
						$query = $this->_db->get($val[0], array($item,'=',$valForm));
						//echo $query->getResults()[0]->id_user;
						//echo $val[1];
							if($query->count()==1) {
								if($query->getResults()[0]->id_user != $val[1]){
									$this->addError(ucfirst($item)." already exists.");
								}
								
							}
						break;
						case 'email':
							if($val === true && !filter_var($valForm, FILTER_VALIDATE_EMAIL)) {
								$this->addError(ucfirst($item)." must be a valid email.");
							}
						break;
						case 'valid':
							if($val === true && !preg_match("/^[a-zA-Z ]*$/",$valForm)) {
								$this->addError(ucfirst($item)." must be a valid name.");
							}
						break;
					}
				}
			}
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}

	private function addError($error){
		$this->_errors []= $error;
	}

	public function errors(){
		return $this->_errors;
	}

	public function passed(){
		return $this->_passed;
	}
}