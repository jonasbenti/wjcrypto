<?php

namespace App\Controller;

use App\Helper\Helper;
use App\Api\Controller\ControllerInterface;
use App\Model\ContasModel;

class HomePage implements ControllerInterface
{
    /** @var Helper $helper */
    private $helper;
    private $html;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->html = file_get_contents('html/index.html');
    }

    public function execute (?array $params = null): void
    {
        //$this->helper->redirect('/');
        //  var_dump(CONTA_WJ);
        //  die;
        // if(!isset(CONTA_WJ['numero_conta'])) {
        //     $this->helper->redirect('/login');
        //     exit;
        // } else {
            echo $this->html;
        // }
        //echo $this->html;
        // $this->helper->redirect('https://www.google.com');
    }

    public function login (?array $params = null): void
    {
        $this->html = file_get_contents('html/login.html');
        //echo $_SESSION['contas_id'] ?? "não tem session";
        //$this->helper->redirect('/');
        echo $this->html;
        // $this->helper->redirect('https://www.google.com');
    }

    public function validarLogin (): void
    {
        //  var_dump($_POST);
        // die;
        // $_SESSION['contas_id'] = $_POST['contas_id'];
        $conta = new ContasModel();
        $conta_wj = $conta->validaConta($_POST['numero_conta'], $_POST['senha']);
        //define('CONTA_WJ', $conta_wj);

        if (!empty($conta_wj)) {
            print_r($conta_wj);
        } else {
            echo "nada";
        }
        die;
        
        
        // print_r($_SESSION);
        // die;
        $this->helper->redirect('/');
        //echo $this->html;
        // $this->helper->redirect('https://www.google.com');
    }


}