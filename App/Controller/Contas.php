<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\ContasModel;
use App\Api\Controller\ControllerInterface;

class Contas implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $contas;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->contas = new ContasModel();
    }

    public function execute (?array $params = null): void
    {
        echo "";
        // try {
        //     $list = $this->contas->load();
        // } catch (Exception $e) {
           
        //     $this->helper->response()->json([
        //         "message" => $e->getMessage()
        //     ]);
        // }
        
        // $this->helper->response()->json([
        //     "message" => "Transfer Success",
        //     "res" => $list
        // ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function getById (string $numero_conta): void
    {

        try {
            $list = $this->contas->edit($numero_conta);
        } catch (Exception $e) {
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
        $this->helper->response()->json([
            "message" => "Conta listada",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function getTransacoesByConta (string $numero_conta): void
    {
        try {
            $list = $this->contas->getTransacoesByConta($numero_conta);
        } catch (Exception $e) {
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
        $this->helper->response()->json([
            "message" => "Transações listadas com sucesso!",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }
   
    public function save (): void
    {
        try {
            $list = $this->contas->save($_POST);
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

    public function getSaldoConta (string $numero_conta): void
    {
        try {
            $saldo = $this->contas->getSaldoByConta($numero_conta);
        } catch (Exception $e) {           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
        $this->helper->response()->json([
            "message" => "Consulta de saldo efetuada com successo!",
            "res" => $saldo
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

}