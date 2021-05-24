<?php
namespace App\Model;

use Exception;
use App\Core\Transaction;
use App\Model\LoggerModel;
use App\Model\ResourceModel\ContasResourceModel;
use Monolog\Handler\Curl\Util;

class ContasModel
{
    private $database = 'wjcrypto';
     

    public function save($param)
    { 
        //try {
            Transaction::open($this->database);
            if(!isset($param['id'])) $param['numero_conta'] = ContasResourceModel::nextConta();
            $param = UtilModel::encrypt($param);
            $result = ContasResourceModel::save($param);
            Transaction::close();

            return $result;            
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }

    public function edit($numero_conta)
    {
        //try {
            Transaction::open($this->database);
            $result= ContasResourceModel::find($numero_conta);
            Transaction::close();
            $result = UtilModel::encrypt($result);

            return $result;
        // } catch (Exception $e) {
        // Transaction::rollback();
        // $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $numero_conta]);
        // $log->createLog();
        // echo $e->getMessage();
        // }
    }

    public function validaConta($numero_conta, $senha)
    {
        //try {
            Transaction::open($this->database);
            $result= ContasResourceModel::findByContaSenha($numero_conta, $senha);
            Transaction::close();

            // $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            // $log->createLog();     
            return $result;
        // } catch (Exception $e) {
        // Transaction::rollback();
        // $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => '']);
        // $log->createLog();
        // echo $e->getMessage();
        // }
    }

    public function getSaldoByConta($numero_conta)
    {
        //try
            Transaction::open($this->database);
            $dados_conta = ContasResourceModel::find($numero_conta);
            $transacoes_conta = ContasResourceModel::findTransacoesByConta($dados_conta['id']);
            Transaction::close();

            $saldo = ContasResourceModel::getSaldo($transacoes_conta);

            return $saldo;
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $numero_conta]);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }

    public function getTransacoesByConta($numero_conta)
    {
        //try
            Transaction::open($this->database);
            $dados_conta = ContasResourceModel::find($numero_conta);
            $transacoes_conta = ContasResourceModel::findTransacoesByConta($dados_conta['id'], true);
            Transaction::close();
            $transacoes_conta = UtilModel::decrypt($transacoes_conta);

            return $transacoes_conta;
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $numero_conta]);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }

    public function load()
    {
        //try{
            Transaction::open($this->database);    
            $result = ContasResourceModel::all();        
            Transaction::close();

            return $result;    
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__, ['result' => $e->getMessage() , 'data' => '']);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }
}