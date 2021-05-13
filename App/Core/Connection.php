<?php

namespace App\Core;

use PDO;
use Exception;

class Connection
{
    private function __construct() {}
    
    
    public static function open($name)
    {
        $file = PATH_INDEX."/App/Config/$name.ini";

        if (file_exists($file))
        {
            $db = parse_ini_file($file);            
        }
        else
        {
            throw new Exception("Arquivo {$name} não encontrado.<br>");
        }
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
        $user = isset($db['user']) ? $db['user'] : null;
        $pass = isset($db['pass']) ? $db['pass'] : null;
        $name = isset($db['name']) ? $db['name'] : null;
        $host = isset($db['host']) ? $db['host'] : null;
        $port = isset($db['port']) ? $db['port'] : '3306';

        $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass, $options);
      
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}