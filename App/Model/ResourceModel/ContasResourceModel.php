<?php

namespace App\Model\ResourceModel;

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

    public static function findTransacoesByConta($contas_id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from transacoes WHERE contas_id= :contas_id");
            $result->execute([':contas_id' => $contas_id]);
            
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function getSaldo(array $transacoes_conta) 
    {
        try {
            $result = 0;
            foreach ($transacoes_conta as $transacao)
            {                     
                if ($transacao['credito_debito'] == 'D') {
                    $result -= $transacao['valor'];
                } else {
                    $result += $transacao['valor'];
                }                
            }    
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function delete($numero_conta)
    {
        if ($conn = Transaction::get()) {
            $sql = "DELETE from contas WHERE numero_conta= :numero_conta";
            $result = $conn->prepare($sql);
            $result->execute([':numero_conta' => $numero_conta]);

            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);        
        }
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