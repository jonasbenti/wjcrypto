<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\LoggerModel;
use App\Model\EnderecosModel;
use App\Api\Controller\ControllerInterface;

class Enderecos implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $enderecos;
    private $numero_conta; 

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->enderecos = new EnderecosModel();
        $this->numero_conta = isset($_SESSION['numero_conta']) ? $_SESSION['numero_conta'] : "API";
    }

    public function execute (?array $params = null): void
    {
        echo "";
    }

    public function getById (int $id): void
    {
        try {
            $result = $this->enderecos->edit($id);
            $log = new LoggerModel('warning', __FUNCTION__." - Endereço ".$this->numero_conta ,['msg' => $result, 'data' => $id]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);      
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Endereço ".$this->numero_conta ,['msg' => $e->getMessage(), 'data' => $id]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }

    public function save (): void
    {
        try {
            $result = $this->enderecos->save($_POST);
            $log = new LoggerModel('warning', __FUNCTION__." - Endereço ".$this->numero_conta ,['msg' => $result, 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);      
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Endereço ".$this->numero_conta ,['msg' => $e->getMessage(), 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }

}