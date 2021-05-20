<?php

namespace App\Controller;

use App\Helper\Helper;
use App\Model\UtilModel;
use App\Model\ContasModel;
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
        //$this->helper->redirect('/');
        //  var_dump(CONTA_WJ);
        //  die;
        // if(!isset(CONTA_WJ['numero_conta'])) {
        //     $this->helper->redirect('/login');
        //     exit;
        // } else {
            session_start();
            echo $this->html;
        // }
        //echo $this->html;
        // $this->helper->redirect('https://www.google.com');
    }

    public function login (?array $params = null): void
    {
        session_start();
        $this->html = file_get_contents('html/login.html');
        //echo $_SESSION['contas_id'] ?? "não tem session";
        //$this->helper->redirect('/');
        echo $this->html;
        // $this->helper->redirect('https://www.google.com');
    }

    public function verificarLogin ()
    {
        $auth_basic = base64_encode("{$_POST['numero_conta']}:{$_POST['senha']}");
 
        $url = "http://trilha2.webjump.com.br/validar/";         
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth_basic"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       
        $response = curl_exec($ch); 

        $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => 'teste']);
        $log->createLog();
        // var_dump(curl_getinfo($ch,CURLINFO_HEADER_OUT));
        // var_dump($response);
        $login = !empty($response) ? '/' : '/login';
        header("Location: $login");
        // if (!$response) {
        //     // $this->helper->redirect('/');
        //     header('Location: $login');
        // } else {
        //     // $this->helper->redirect('/login');
        //     header('Location: /login');
        // }
        

        // return "teste"; /// curl_exec($ch);         
        // //die('teste');
        // $this->helper->redirect('/');
    }

    public function validarLogin ()
    {
        $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['tela' => 'validar Login']);
        $log->createLog();

        $auth = UtilModel::authToken();
        $conta = new ContasModel();
        $conta_wj = $conta->validaConta($auth['numero_conta'], $auth['senha']);

        return json_encode($conta_wj);
    }


}