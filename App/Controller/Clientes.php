<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\LoggerModel;
use App\Model\ClientesModel;
use App\Api\Controller\ControllerInterface;

class Clientes implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $clientes;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->clientes = new ClientesModel();
    }

    public function execute (?array $params = null): void
    {
        echo "";
    }

    public function getById (int $id): void
    {
        try {
            $result = $this->clientes->edit($id);
            $log = new LoggerModel('warning', __FUNCTION__." - Cliente ",['msg' => $result, 'data' => $id]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);      
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Cliente ",['msg' => $e->getMessage(), 'data' => $id]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }

    public function save (): void
    {
        try {
            $result = $this->clientes->save($_POST);
            $log = new LoggerModel('warning', __FUNCTION__." - Cliente ",['msg' => $result, 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $result
            ]);      
        } catch (Exception $e) {
            $log = new LoggerModel('error', __FUNCTION__." - Cliente ",['msg' => $e->getMessage(), 'data' => $_POST]);
            $log->createLog();
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }

}