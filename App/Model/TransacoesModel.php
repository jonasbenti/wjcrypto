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

    public function edit($id)
    {
        Transaction::open($this->database);
        $result= TransacoesResourceModel::find($id);
        Transaction::close();

        return $result;
    }

    public function load()
    {
        Transaction::open($this->database);
        $result = TransacoesResourceModel::all();
        Transaction::close();

        return $result;        
    }

    public function createTransacao($param)
    {
        Transaction::open($this->database);
        $numero_conta_trans = $param['conta_transferencia_id'] ?? 0;

        $transacoes_conta = ContasResourceModel::findTransacoesByConta($param['contas_id']);
        //die(var_dump($transacoes_conta));
        $transacoes_conta = UtilModel::decrypt($transacoes_conta);
        //die(var_dump($transacoes_conta));
        //die(var_dump($transacoes_conta));
        $saldo = ContasResourceModel::getSaldo($transacoes_conta);
        // die(var_dump($saldo));

        if ($param['tipo_transacao_id'] == '3') {
            $conta_transferencia = ContasResourceModel::find($numero_conta_trans);
            if(empty($conta_transferencia))
            throw new Exception("Não foi possivel realizar operação de transferência! Conta $numero_conta_trans inativa ou não existe!");

            $param['conta_transferencia_id'] = $conta_transferencia['id'];
        }            

        if (($param['credito_debito'] == 'D') && ($saldo < $param['valor'])) {
            throw new Exception("Saldo insuficiente! você possui $saldo, e tentou sacar/transferir {$param['valor']}!");
        } 

        if(!isset($param['id']))
        $param['numero_transacao'] = TransacoesResourceModel::nextTransacao();

        $param = UtilModel::encrypt($param);
        TransacoesResourceModel::save($param);
        //die(var_dump($param));


        $transacoes_conta_fim = ContasResourceModel::findTransacoesByConta($param['contas_id']);
        // (var_dump($transacoes_conta_fim));
        $transacoes_conta_fim = UtilModel::decrypt($transacoes_conta_fim);
        //die(var_dump($transacoes_conta_fim));
        //die(var_dump(count($transacoes_conta_fim, COUNT_RECURSIVE)));
        $saldo_fim = ContasResourceModel::getSaldo($transacoes_conta_fim);
        // die(var_dump($saldo_fim));
        //die(var_dump($param));
        $param = UtilModel::decrypt($param);
        //die(var_dump($param));
        if (($param['tipo_transacao_id'] == '3') && ($param['credito_debito'] == 'D')) {
            $transacao_receiver = TransacoesResourceModel::receiverTransferencia($param); 
            //die(var_dump($transacao_receiver));
            $transacao_receiver = UtilModel::encrypt($transacao_receiver);               
            TransacoesResourceModel::save($transacao_receiver);                  
        }
        Transaction::close();
        $result = "Transação realizada com sucesso! Seu saldo atual é: $saldo_fim! Seu saldo anterior era $saldo";                      
        //die($result);
        return $result;
    }
}