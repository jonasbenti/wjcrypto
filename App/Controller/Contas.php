<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\UtilModel;
use App\Model\ContasModel;
use App\Model\LoggerModel;
use App\Model\ContasClienteModel;
use App\Api\Controller\ControllerInterface;

class Contas implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $contas;
    private $contasCliente;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->contas = new ContasModel();
        $this->contasCliente = new ContasClienteModel();
    }

    public function execute (?array $params = null): void
    {
        echo "";
    }

    public function verificarConta ()
    {
        $message = "erro ao logar! verifique seu login e senha";
        $senha = base64_encode($_POST['senha']);
        $auth_basic = base64_encode("{$_POST['numero_conta']}:{$senha}");
        $url = "http://trilha1.webjump.com.br/contas_validar/"; 
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth_basic"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);       
        $response = json_decode(curl_exec($ch),true);
                 
        if (isset($response['numero_conta'])) {
            $_SESSION['auth'] = $auth_basic;
            $_SESSION['numero_conta'] = $response['numero_conta'];
            $_SESSION['contas_id'] = $response['id'];
            $message = "logado com sucesso";
        }

        $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['message' => $message, 'res' => $response]);
        $log->createLog();
        header("Location: /");
    }

    public function validarConta ()
    {
        $result = UtilModel::authToken();
        return json_encode($result);
    }

    public function getById (string $numero_conta): void
    {
        try {
            $result = $this->contas->edit($numero_conta);
            $log = new LoggerModel('warning', __FUNCTION__." - Conta",['msg' => $result, 'data' => $numero_conta]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result              
            ]);
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Conta",['msg' => $e->getMessage(), 'data' => $numero_conta]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }

    public function getTransacoesByConta (string $numero_conta): void
    {
        try {
            $result = $this->contas->getTransacoesByConta($numero_conta);
            $log = new LoggerModel('warning', __FUNCTION__." - Conta",['msg' => $result, 'data' => $numero_conta]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result              
            ]);
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Conta",['msg' => $e->getMessage(), 'data' => $numero_conta]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }
   
    public function save (): void
    {
        try {
            $result = $this->contas->save($_POST);
            $log = new LoggerModel('warning', __FUNCTION__." - Conta",['msg' => $result, 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);
        } catch (Exception $e) {           
            $log = new LoggerModel('error', __FUNCTION__." - Conta",['msg' => $e->getMessage(), 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }       
    }

    public function getSaldoConta (string $numero_conta): void
    {
        try {
            $result = $this->contas->getSaldoByConta($numero_conta);
            $log = new LoggerModel('warning', __FUNCTION__." - Conta",['msg' => $result, 'data' => $numero_conta]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);
        } catch (Exception $e) {           
            $log = new LoggerModel('error', __FUNCTION__." - Conta",['msg' => $e->getMessage(), 'data' => $numero_conta]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }       
    }

    public function saveContaCliente (): void
    {
        try {
            $result = $this->contasCliente->save($_POST);
            $log = new LoggerModel('warning', __FUNCTION__." - Conta",['msg' => $result, 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);
        } catch (Exception $e) {           
            $log = new LoggerModel('error', __FUNCTION__." - Cadastro Usuario",['msg' => $e->getMessage(), 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }       
    }



}