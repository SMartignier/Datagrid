<?php
class App
{
	private static $config = array();
	private static $database;
	
	// Singleton
	private static $_instance = null;
	public static function init($config) {
		if(is_null(self::$_instance)){
			$c = __CLASS__;
			//$c = get_called_class(); 
			self::$_instance = new $c($config);
		}
		return self::$_instance;
	}
	
	protected function __construct($config)
	{
		if(count($config["db"]) != 4){
			throw new \Exception("Le nombre d'arguments n'est pas valable!");
		}
		spl_autoload_register(array(__CLASS__, 'autoload'));
		self::$config = $config;
		self::$database = new Core\Db\ConnectPDO(self::$config["db"]); 
	}
	
	static function autoload($className){
		$file = 'src/classes/'.str_replace('\\', '/', $className).'.class.php';
		if(file_exists($file))
			require_once $file; 
		else
			throw new \Exception("Fichier ".$file." introuvable");
    }
	
	public static function db()
	{
		if(empty(self::$config)){
			throw new \Exception("Veuillez exécuter App::init() auparavant!");
		}
		return self::$database;
	}
}
?>