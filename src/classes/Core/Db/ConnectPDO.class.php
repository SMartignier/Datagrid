<?php
namespace Core\Db;

final class ConnectPDO extends ConnectDB
{ 
	private $_pdo;
	
	public function __construct($args)
	{
		parent::__construct($args);
	}
	
	protected function connect()
	{
		$strConnection = 'mysql:host='.$this->host.';dbname='.$this->dbname; 
		$arrExtraParam= array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
		$this->_pdo = new \PDO($strConnection, $this->user, $this->password, $arrExtraParam); 
		$this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	protected function disconnect() 
	{
		$this->_pdo = null;
	}
	
	public function query($sql)
	{
		if(is_string($sql)){
			$result = $this->_pdo->query($sql);
			return $result;
		}
		else{
			throw new \Exception("La requête n'est pas valide!");
			return false; 
		}
	}
	
	public function fetch($sql)
	{
		$return = array();
		if($result = $this->query($sql))
		{
			while($val=$result->fetch(\PDO::FETCH_ASSOC))
			{
				$return[] = $val;
			}
			return $return;
		}
		else{
			throw new \Exception("La requête n'est pas valide!");
			return false; 
		}
	}
	
	public function listTables(){
		$tableNames = array();
		$result = $this->_pdo->query("show tables");
		while ($row = $result->fetch(\_pdo::FETCH_NUM)) {
			$tableNames[] = $row[0];
		}
		return $tableNames;
	}		
	
	
	/* Fonctions supplémenatires liées à une table */
	
	public function nbFields($tableName)
	{  
		$result = $this->_pdo->query('SELECT * FROM '.$tableName);
		return $result->ColumnCount();
	}
	
	public function getPK($tableName)
	{
		$result = $this->_pdo->query('SHOW KEYS FROM '.$tableName.' WHERE Key_name = "PRIMARY"');
		$keys = $result->fetch();
		return $keys["Column_name"];
	}
	
	public function getFieldsNames($tableName)
	{
		$fieldsNames = array();
		$result = $this->_pdo->query('SELECT * FROM '.$tableName);
		for ($i=0; $i<$result->ColumnCount();$i++){
			$meta = $result->getColumnMeta($i); 
			$fieldsNames[] = $meta['name'];
		}
		return $fieldsNames;
	}
	
	public function getNotNullFields($tableName)
	{
		$notNullFields = array();
		$result = $this->_pdo->query('SELECT * FROM '.$tableName);
		for ($i=0; $i<$result->ColumnCount();$i++){
			$meta = $result->getColumnMeta($i); 
			$fieldsFlags = $meta['flags'];
			foreach($fieldsFlags as $key => $value){
				if($value == 'not_null' && $meta['name'] != $this->getPK($tableName)){
					$notNullFields[] = $meta['name'];
				}
			}
		}
		return $notNullFields;
	}
}
?>