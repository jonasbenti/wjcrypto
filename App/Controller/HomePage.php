<?php

namespace App\Controller;

use App\Helper\Helper;
use App\Model\LoggerModel;
use App\Api\Controller\ControllerInterface;

class HomePage implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $html;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->html = file_get_contents('html/index.html');
    }

    public function execute (?array $params = null): void
    {
        $this->html = str_replace('{numero_conta}', $_SESSION['numero_conta'], $this->html);    
        echo $this->html;
    }

    public function login (?array $params = null): void
    {
        if (isset($_SESSION['auth']) && !empty($_SESSION['auth']) && !empty($_SESSION['numero_conta'])) { // encontrou basic auth
            $this->html = file_get_contents('html/logado.html');
        } else {
            $this->html = file_get_contents('html/login.html');            
        }
        echo $this->html;
    }

    public function cadastro (?array $params = null): void
    {
        $this->html = file_get_contents('html/form_cadastro.html');
        echo $this->html;
    }

    public static function logout () : void
    {
        $log = new LoggerModel('warning', __FUNCTION__." - Logout",['msg' => "Deslogado com sucesso", 'data' => '']);
        $log->createLog();
        session_destroy();
        header('Location: /login');        
    }
}