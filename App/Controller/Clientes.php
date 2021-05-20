<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
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
            $list = $this->clientes->edit($id);
        } catch (Exception $e) {
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
        $this->helper->response()->json([
            "message" => "Cliente listado",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function save (): void
    {
        try {
            $list = $this->clientes->save($_POST);
        } catch (Exception $e) {
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
        $this->helper->response()->json([
            "message" => "Salvo com successo!",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

}