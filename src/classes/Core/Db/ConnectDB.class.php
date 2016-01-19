<?php
namespace Core\Db;

abstract class ConnectDB implements iConnect
{
	protected $host;
	protected $user;
	protected $password;
	protected $dbname;
	
	public function __construct($args)
	{
		//if(func_num_args() != 4){
		if(count($args) != 4){
			throw new \Exception("Le nombre d'arguments n'est pas valable!");
		}

		foreach($args as $key=>$value){
			$this->$key = $value; 
		}
		
		$this->connect();
	}
	
	public function __destruct()
	{
		$this->disconnect();
	}
	
	protected abstract function connect();
	protected abstract function disconnect();
	
	public function __clone()
	{
		throw new \Exception("Clonage du Singleton impossible!");
	}
	
	public function __toString()
	{
		$return = "";
		foreach($this as $key => $value){
			$return .= $key .' = ' . $value . "<br />";
		}
		return $return;
	}
}
?>