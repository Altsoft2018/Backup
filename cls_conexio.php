<?php
include_once 'config.php';	

class ConectarDB
{
    private static $instance;
    private $counter;
    private $host = DB_HOST;
	private	$dbname = DB_NAME;
	private	$username = DB_USER;
	private	$password = DB_PASSWORD;


    private function __construct()
    {
      
    }
 
    public static function InstanciaUnica()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function cnx()
    {
        try {
            $tmp = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
        	$tmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $tmp->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $tmp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $tmp->exec("set names utf8mb4");
            ++$this->counter;
            return ($tmp);

        } catch (PDOException $e) {
            $Error = ' *** Error ***';
            switch ($e->getCode()) {
                case 1045:
                    $Error .= ' (1045) - ';
                    $Error .=  'Usuari [' .  $this->username . '] no autoritzat';
                    break;
                case 1049:
                    $Error .= ' (1049) - ';
                    $Error .= 'La base de dades [' . $this->dbname . '] no existeix';
                    break;
                case 2002:
                    $Error .=  ' (2002) - ';
                    $Error .=  'Sense conexió amb [' .$this->host .']';
                    break;
                default:
                $Error .=   'Error ' . $e->getCode();
                    break;
            }
            if(LOGG_STATE){ echo $Error;}
            exit();
        }
       
    }
    
}


