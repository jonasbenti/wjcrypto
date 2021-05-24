<?php

namespace App\Model\ResourceModel;

use PDO;
use Exception;
use App\Core\Transaction;

class TipoTransacaoResourceModel
{
    public static function find($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from tipo_transacao WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function all()
    {
        if ($conn = Transaction::get()) { 
            $result = $conn->query("select * from tipo_transacao ORDER BY id desc");

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function save($tipo_transacao)
    {
        if ($conn = Transaction::get()) {
            foreach ($tipo_transacao as $key => $value) {
                $tipo_transacao[$key] = strip_tags(addslashes($value));
            }
            $id = isset($tipo_transacao['id']) ? $tipo_transacao['id'] : 0;
            unset($tipo_transacao['id']);
           
            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($tipo_transacao));
                $values_insert = "'".implode("', '",array_values($tipo_transacao))."'";
                $sql = "INSERT INTO tipo_transacao ($keys_insert) VALUES ($values_insert)";

            } else {
                $set = [];
                foreach ($tipo_transacao as $column => $value) {
                    $set[] = "$column = '$value'";
                }
                $set_update = implode(", ", $set);
                $sql = "UPDATE tipo_transacao SET $set_update WHERE id = '$id'";
            }            
            $result = $conn->query($sql);            
            
            return $result;
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);            
        }
    }    
}