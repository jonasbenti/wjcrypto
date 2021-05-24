<?php

namespace App\Model\ResourceModel;

use App\Controller\Contas;
use PDO;
use Exception;
use App\Core\Transaction;

class ContasResourceModel
{
    public static function find($numero_conta)
    {
        if ($conn = Transaction::get()) {    
            $sql = "select * from contas WHERE numero_conta = :numero_conta";        
            $result = $conn->prepare($sql);
            $result->execute([':numero_conta' => $numero_conta]);
            $contas = $result->fetch(PDO::FETCH_ASSOC);
            
            return $contas;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function findByContaSenha($numero_conta, $senha)
    {
        if ($conn = Transaction::get()) { 
            $sql = "select * from contas WHERE numero_conta = :numero_conta and senha = :senha";            
            $result = $conn->prepare($sql);
            $result->execute([':numero_conta' => $numero_conta, ':senha' => $senha]);
            $contas = $result->fetch(PDO::FETCH_ASSOC);
                        
            return $contas;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function findTransacoesByConta(int $contas_id, bool $join = false)
    {
        if ($conn = Transaction::get()) {      
            $sql = "select * from transacoes WHERE contas_id= :contas_id ORDER BY id desc";      
            $sql_join = "select t.numero_transacao, tp.descricao as tipo_transacao,
            ct.numero_conta as conta_transferencia_id, t.credito_debito as credito_debito,
            t.valor, date_format(t.created_at, '%d/%m/%Y %H:%i:%s') as created_at
            from transacoes t
            inner join tipo_transacao tp on (t.tipo_transacao_id = tp.id)
            left join contas ct on (t.conta_transferencia_id = ct.id)  
            WHERE t.contas_id= :contas_id ORDER BY t.id desc";
            $sql = ($join) ? $sql_join : $sql ;
            
            $result = $conn->prepare($sql);
            $result->execute([':contas_id' => $contas_id]);
            
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function getSaldo(array $transacoes_conta) 
    {
        
        $result = 0;
        foreach ($transacoes_conta as $transacao)
        {                     
            if ($transacao['credito_debito'] == 'D' || $transacao['credito_debito'] == 'Débito') {
                $result -= (double) $transacao['valor'];
            } else {
                $result += (double) $transacao['valor'];
            }                
        }    
        return $result;
    }

    public static function all()
    {
        if ($conn = Transaction::get()) { 
            $result = $conn->query("select * from contas ORDER BY id desc");

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function save($contas)
    {
        if ($conn = Transaction::get()) {
            foreach ($contas as $key => $value) {
                $contas[$key] = strip_tags(addslashes($value));
            }
            $id = isset($contas['id']) ? $contas['id'] : 0;
            unset($contas['id']);
            //$contas['senha'] = md5($contas['senha']);

            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($contas));
                $values_insert = "'".implode("', '",array_values($contas))."'";
                $sql = "INSERT INTO contas ($keys_insert) VALUES ($values_insert)";

            } else {
                $set = [];
                foreach ($contas as $column => $value) {
                    $set[] = "$column = '$value'";
                }
                $set_update = implode(", ", $set);
                $sql = "UPDATE contas SET $set_update WHERE id = '$id'";
            }
            //die($sql);

            return $conn->query($sql);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }    

    public static function nextConta()
    {
        if ($conn = Transaction::get()) { 
            $result = $conn->query("select MAX(numero_conta) as next_conta from contas ORDER BY id desc");
            $conta = $result->fetch(PDO::FETCH_ASSOC);
            $next_conta = ($conta['next_conta'] ?: 0) + 1;
            $next_conta = str_pad($next_conta, 6,"0", STR_PAD_LEFT);
            
            return $next_conta;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }
}