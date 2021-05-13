<?php

namespace App\Model;

use Exception;
use App\Core\Transaction;
use App\Model\ResourceModel\ContasResourceModel;
use App\Model\LoggerModel;
use App\Model\ResourceModel\TransacoesResourceModel;

class TransacoesModel
{
    private $database = 'wjcrypto';     

    public function save($param)
    { 
        try {
            Transaction::open($this->database);
            $result = TransacoesResourceModel::save($param);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();
            
            return $result;  
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
            $log->createLog();
            echo $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            Transaction::open($this->database);
            $result= TransacoesResourceModel::find($id);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();

            return $result;

        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
            $log->createLog();
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            Transaction::open($this->database);
            $result = TransacoesResourceModel::delete($id);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();

            return $result;
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
            $log->createLog();
            echo $e->getMessage();
        }
    }

    public function load()
    {
        try 
        {
            Transaction::open($this->database);
            $result = TransacoesResourceModel::all();
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();
            
            return $result;
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => '']);
            $log->createLog();
            echo $e->getMessage();
        }
    }

    public function createTransacao($param)
    {
        try {
            Transaction::open($this->database);

            $transacoes_conta = ContasResourceModel::findTransacoesByConta($param['contas_id']);

            $saldo = ContasResourceModel::getSaldo($transacoes_conta);

            if (($param['credito_debito'] == 'D') && ($saldo < $param['valor'])) {
                throw new Exception("Saldo insuficiente! você possui $saldo, e tentou sacar/transferir {$param['valor']}!");
            } 

            if(!isset($param['id']))
            $param['numero_transacao'] = TransacoesResourceModel::nextTransacao();

            TransacoesResourceModel::save($param);

            $transacoes_conta_fim = ContasResourceModel::findTransacoesByConta($param['contas_id']);
            $saldo_fim = ContasResourceModel::getSaldo($transacoes_conta_fim);
              
            if (($param['tipo_transacao_id'] == '3') && ($param['credito_debito'] == 'D')) {
                $transacao_receiver = TransacoesResourceModel::receiverTransferencia($param);
                
                TransacoesResourceModel::save($transacao_receiver);                  
            }
            Transaction::close();
            $result = "Transação realizada com sucesso! Seu saldo atual é: $saldo_fim! Seu saldo anterior era $saldo";                      
            
            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['msg' => $result]);
            $log->createLog();

            return $result;
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
            $log->createLog();
            echo $e->getMessage();
        }
    }
}