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
    // private $html;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->transacoes = new TransacoesModel();
        // $this->html = file_get_contents('html/list_transacoes.html');
    }

    public function execute (?array $params = null): void
    {
        $html = file_get_contents('html/transacao.html');        
        echo $html;
        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function extract (?array $params = null): void
    {
        $items = '';
        $html = file_get_contents('html/list_transacoes.html');  

        $url = "http://trilha2.webjump.com.br/transacao/";
        // $get = file_get_contents($url);
        // $result = json_decode($get, true);
        // var_dump($result['res']);
        //url = "https://www.canalti.com.br/api/pokemons.json"; 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $transacoes = json_decode(curl_exec($ch), true);
        
        foreach($transacoes['res'] as $transacao){
            $item = file_get_contents('html/item_transacoes.html');
            foreach($transacao as $index => $value){
                if($index != 'updated_at') {              
                $item = str_replace('{'.$index.'}', $value, $item);
                }            
            }
            $items .= $item;
        }
        $html = str_replace('{items}', $items, $html);
        //var_dump($result);
        //die;
        echo $html;

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function list (?array $params = null): void
    {
        try {
            $list = $this->transacoes->load();
        } catch (Exception $e) {           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
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
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
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
            $result = $this->transacoes->createTransacao($_POST);
            $this->helper->response()->json([
                "message" => "Salvo com sucesso!",
                "res" => $result
            ]);
            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['msg' => $result , 'data' => $_POST]);
            $log->createLog();
            
        } catch (Exception $e) {
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

    public function delete (int $id): void
    {
        try {
            $list = $this->transacoes->delete($id);
        } catch (Exception $e) {
           
            $this->helper->response()->json([
                "message" => $e->getMessage()
            ]);
        }
        
        $this->helper->response()->json([
            "message" => "Deletado Success $id",
            "res" => $list
        ]);

        //$this->helper->redirect('/html/index.html');
        // $this->helper->redirect('https://www.google.com');
    }

}