<?php

namespace App\Model\ResourceModel;

use PDO;
use Exception;
use App\Core\Transaction;

class TransacoesResourceModel
{
    public static function find($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from transacoes WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function delete($id)
    {
        if ($conn = Transaction::get()) {
            $sql = "DELETE from transacoes WHERE id= :id";
            $result = $conn->prepare($sql);
            $result->execute([':id' => $id]);

            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function all()
    {
        if ($conn = Transaction::get()) { 
            $result = $conn->query("select * from transacoes ORDER BY id desc");

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function save($transacoes)
    {              
        if ($conn = Transaction::get()) {
            foreach ($transacoes as $key => $value) {
                $transacoes[$key] = strip_tags(addslashes($value));
            }
            $id = isset($transacoes['id']) ? $transacoes['id'] : 0;
                        
            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($transacoes));
                $values_insert = "'".implode("', '",array_values($transacoes))."'";
                $sql = "INSERT INTO transacoes ($keys_insert) VALUES ($values_insert)";

            } else {
                throw new Exception('As Transações não podem ser alteradas');
            }

            $result = $conn->query($sql);
                        
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);            
        }
    } 

    public static function nextTransacao()
    {
        if ($conn = Transaction::get()) { 
            $result = $conn->query("select MAX(numero_transacao) as next_transacao from transacoes ORDER BY id desc");
            $transacao = $result->fetch(PDO::FETCH_ASSOC);
            $next_transacao = ($transacao['next_transacao'] ?: 0) + 1;
            $next_transacao = str_pad($next_transacao, 6,"0", STR_PAD_LEFT);
            
            return $next_transacao;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function receiverTransferencia(array $send_transacao)
    {
        $transacao_receiver = $send_transacao;
        if (!empty($transacao_receiver['conta_transferencia_id'])) { 
            $transacao_receiver['credito_debito'] = 'C';
            $transacao_receiver['conta_transferencia_id'] = $send_transacao['contas_id'];
            $transacao_receiver['contas_id'] = $send_transacao['conta_transferencia_id'];

            return $transacao_receiver;
        } else {
            throw new Exception('Transferência não possui conta de destino!'.__FUNCTION__);
        }
    }
}