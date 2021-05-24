<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\LoggerModel;
use App\Model\TransacoesModel;
use App\Api\Controller\ControllerInterface;

class Transacoes implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $transacoes;
    private $numero_conta;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->transacoes = new TransacoesModel();
        $this->numero_conta = isset($_SESSION['numero_conta']) ? $_SESSION['numero_conta'] : "API";
    }

    public function execute (?array $params = null): void
    {
        $html = file_get_contents('html/form_transacao.html');  
        $html = str_replace('{token}', $_SESSION['auth'], $html);      
        $html = str_replace('{numero_conta}', $_SESSION['numero_conta'], $html);  
        $html = str_replace('{contas_id}', $_SESSION['contas_id'], $html);  
            
        echo $html;
    }

    public function extract (string $numero_conta): void
    {
        $items = '';
        $html = file_get_contents('html/list_transacoes.html');  

        $auth_basic = $_SESSION['auth'];
        $url = "http://trilha1.webjump.com.br/contas_transacoes/".$numero_conta;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth_basic"));
        $transacoes = json_decode(curl_exec($ch), true);
        
        foreach($transacoes['message'] as $transacao){
            $item = file_get_contents('html/item_transacoes.html');
            extract($transacao);
            $valor =  str_replace('.', ',', $valor);
            $credito_debito = ($credito_debito == 'C') ? 'Crédito' : 'Débito' ;
            $item = str_replace('{numero_transacao}', $numero_transacao, $item);
            $item = str_replace('{tipo_transacao}', $tipo_transacao, $item);
            $item = str_replace('{conta_transferencia_id}', $conta_transferencia_id, $item);
            $item = str_replace('{credito_debito}', $credito_debito, $item);
            $item = str_replace('{valor}', $valor, $item);
            $item = str_replace('{created_at}', $created_at, $item);

            $items .= $item;
        }
        $html = str_replace('{numero_conta}', $numero_conta, $html);
        $html = str_replace('{items}', $items, $html);
        $log = new LoggerModel('warning', __FUNCTION__." - Conta ".$numero_conta,['msg' => 'Acesso Lista de Transações', 'data' => $transacoes]);
        $log->createLog();
        echo $html;
    }

    public function list (?array $params = null): void
    {
        try {
            $result = $this->transacoes->load();
            $log = new LoggerModel('warning', __FUNCTION__." - Conta ".$this->numero_conta ,['msg' => $result, 'data' => '']);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);      
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Conta ".$this->numero_conta ,['msg' => $e->getMessage(), 'data' => '']);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }      
    }

    public function getById (int $id): void
    {
        try {
            $result = $this->transacoes->edit($id);
            $log = new LoggerModel('warning', __FUNCTION__." - Conta ".$this->numero_conta ,['msg' => $result, 'data' => $id]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);      
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Conta ".$this->numero_conta ,['msg' => $e->getMessage(), 'data' => $id]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }      
    }

    public function createTransacao (): void
    {
        try {
            $result = $this->transacoes->createTransacao($_POST);
            $log = new LoggerModel('warning', __FUNCTION__." - Conta ".$this->numero_conta ,['msg' => $result, 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);          
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Conta ".$this->numero_conta ,['msg' => $e->getMessage(), 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        } 
    }
}