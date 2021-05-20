<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\EnderecosModel;
use App\Api\Controller\ControllerInterface;

class Enderecos implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $enderecos;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->enderecos = new EnderecosModel();
    }

    public function execute (?array $params = null): void
    {
        echo "";
    }

    public function getById (int $id): void
    {
        try {
            $list = $this->enderecos->edit($id);
        } catch (Exception $e) {
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
        $this->helper->response()->json([
            "message" => "Endereco listado",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function save (): void
    {
        try {
            $list = $this->enderecos->save($_POST);
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