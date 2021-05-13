<?php
namespace App\Model;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerModel
{
    private $type;
    private $entity;
    private $array_detail;
    private $name;
    private $filepath;

    public function __construct(
        $type_log = "warning", $entity_log = '', $array_detail_log = [], $name_log = "wjcrypto_log",
        $filepath_log = PATH_INDEX."/wjcrypto.log"
        ) 
    {
        $this->type = $type_log;
		$this->entity = $entity_log;
		$this->array_detail = $array_detail_log;
		$this->name = $name_log;
		$this->filepath = $filepath_log;
    }     

    public function createLog()
    { 
        $type = $this->type;
        
        $log = new Logger($this->name);
        $log->pushHandler(new StreamHandler($this->filepath, Logger::WARNING));
        $log->$type($this->entity, $this->array_detail);  
    }
}