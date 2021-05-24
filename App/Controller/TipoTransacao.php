<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\LoggerModel;
use App\Model\TipoTransacaoModel;
use App\Api\Controller\ControllerInterface;

class TipoTransacao implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $tipo_transacao;
    private $numero_conta; 
    
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->tipo_transacao = new TipoTransacaoModel();
        $this->numero_conta = isset($_SESSION['numero_conta']) ? $_SESSION['numero_conta'] : "API";
    }

    public function execute (?array $params = null): void
    {
        try {
            $result = $this->tipo_transacao->load();
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
            $result = $this->tipo_transacao->edit($id);
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

    public function save (): void
    {
        try {
            $result = $this->tipo_transacao->save($_POST);
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