<?php
namespace Core\Db;

interface iConnect{
	public function query($sql);
	public function fetch($sql);
	public function listTables();
	public function nbFields($tableName);
	public function getFieldsNames($tableName);
	public function getPK($tableName);
	public function getNotNullFields($tableName);
}
?>