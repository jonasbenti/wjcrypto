<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\TransacoesModel;
use App\Api\Controller\ControllerInterface;

class Transacoes implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $transacoes;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->transacoes = new TransacoesModel();
    }

    public function execute (?array $params = null): void
    {
        try {
            $list = $this->transacoes->load();
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Lista carregada",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function getById (int $id): void
    {
        try {
            $list = $this->transacoes->edit($id);
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Registro carregado com sucesso!",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function createTransacao (): void
    {
        try {
            $list = $this->transacoes->createTransacao($_POST);
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Salvo com sucesso!",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function delete (int $id): void
    {
        try {
            $list = $this->transacoes->delete($id);
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Deletado Success $id",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

}