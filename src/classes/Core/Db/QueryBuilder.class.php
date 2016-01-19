<?php
namespace Core\Db;

use \App;

class QueryBuilder
{
	private $_db;
	private $_query = array();
	
	public function __construct($db=null){
		$this->_db = $db;
	}
	
	public function select(){
		$args = func_get_args();
        $this->_query["fields"] = empty($args) ? array('*') : $args;
        return $this;
    }

    public function from($table, $alias = null){
        if(is_null($alias)){
            $this->_query["from"][] = $table;
        }else{
            $this->_query["from"][] = "$table AS $alias";
        }
        return $this;
    }
	
	public function where(){
		$args = func_get_args();
        foreach($args as $arg){
            $this->_query["conditions"][] = $arg;
        }
        return $this;
    }
	
	public function order($by, $order='ASC'){
		$this->_query["order"][] = $by.' '.$order;
        return $this;
    }
	
	public function limit($start=0, $length=0){
		$this->_query["limit"]["start"] = $start;
		$this->_query["limit"]["length"] = $length;
        return $this;
	}
	
	public function getSql()
	{
		$return = '';
		$return .= 'SELECT '. implode(', ', $this->_query["fields"]);
		$return .= ' FROM ' . implode(', ', $this->_query["from"]);
		
		if(!empty($this->_query["conditions"]))
			$return .= ' WHERE ' . implode(' AND ', $this->_query["conditions"]);
			
		if(!empty($this->_query["order"]))
			$return .= ' ORDER BY ' . implode(', ', $this->_query["order"]);
			
		if(@$this->_query["limit"]["length"] > 0)
			$return .= ' LIMIT ' . $this->_query["limit"]["start"] . ', ' . $this->_query["limit"]["length"];
		
        return $return;
	}
	
	public function __toString()
	{
		return $this->getSql();
	}
	
	private function checkIfIdExists($tableName, $id)
	{
		$primaryKey = $this->_db->getPK($tableName);
		$request = $this->select()->from($tableName)->where($primaryKey.'='.$id); 
		if(!$result = $this->_db->query($request->getSql()))
			return false; 
		else
			return true;
	}
	
	public function update($tableName, $id, $attributes)
	{
		if(!$this->checkIfIdExists($tableName, $id)){
			throw new \Exception("Cet enregistrement n'existe pas!");
			return false; 
		}
		
		$primaryKey = $this->_db->getPK($tableName);
		$lines = array();
		foreach($attributes as $key=>$value){
			if($key != $primaryKey){
				$lines[] = $key."='".$value."'";
			}
		}
		$sql = "UPDATE ".$tableName." SET ".implode(", ", $lines). " WHERE ".$primaryKey."='".$id."'";
		echo $sql;
	}
	
	public function insert($tableName, $attributes)
	{
		foreach($this->_db->getNotNullFields($tableName) as $notNullField){
			if(!$attributes[$notNullField]){
				throw new \Exception("Renseignez tous les champs obligatoires!"); 
				return false; 
			}
		}
		
		$primaryKey = $this->_db->getPK($tableName);
		$values = array();
		foreach($attributes as $key=>$value){
			if($key != $primaryKey){
				$values[$key] = $value;
			}
		}
		$sql = "INSERT INTO ".$tableName." (".implode(", ", array_keys($values)).") VALUES ('".implode("', '", $values)."')";
		echo $sql;
	}
	
	public function delete($tableName, $id)
	{
		if(!$this->checkIfIdExists($tableName, $id)){
			throw new \Exception("Cet enregistrement n'existe pas!");
			return false; 
		}
		
		$primaryKey = $this->_db->getPK($tableName);
		$sql = "DELETE FROM ".$tableName." WHERE ".$primaryKey." = '".$id."'";
		echo $sql;
	}
}
?>