<?php

namespace App\Controller;

use Exception;
use App\Helper\Helper;
use App\Model\TipoTransacaoModel;
use App\Api\Controller\ControllerInterface;

class TipoTransacao implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $tipo_transacao;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->tipo_transacao = new TipoTransacaoModel();
    }

    public function execute (?array $params = null): void
    {
        try {
            $list = $this->tipo_transacao->load();
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Transfer Success",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function getById (int $id): void
    {
        try {
            $list = $this->tipo_transacao->edit($id);
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Item listado",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function save (): void
    {
        try {
            $list = $this->tipo_transacao->save($_POST);
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Salvo com successo!",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function delete (int $id): void
    {
        try {
            $list = $this->tipo_transacao->delete($id);
        } catch (Exception $e) {
           // $this->logger->log('Transfer Error: ', $_POST);
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->logger->log('Transfer Success: ', $_POST);
        $this->helper->response()->json([
            "message" => "Deletado com successo! ID: $id",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

}