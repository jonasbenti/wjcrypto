<?php

namespace App\Model\ResourceModel;

use PDO;
use Exception;
use App\Core\Transaction;

class EnderecosResourceModel
{
    public static function find($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from enderecos WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function delete($id)
    {
        if ($conn = Transaction::get()) {
            $sql = "DELETE from enderecos WHERE id= :id";
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
            $result = $conn->query("select * from enderecos ORDER BY id desc");

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function save($enderecos)
    {
        if ($conn = Transaction::get()) {
            foreach ($enderecos as $key => $value) {
                $enderecos[$key] = strip_tags(addslashes($value));
            }
            $id = isset($enderecos['id']) ? $enderecos['id'] : 0;
            unset($enderecos['id']);

            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($enderecos));
                $values_insert = "'".implode("', '",array_values($enderecos))."'";
                $sql = "INSERT INTO enderecos ($keys_insert) VALUES ($values_insert)";

            } else {
                $set = [];
                foreach ($enderecos as $column => $value) {
                    $set[] = "$column = '$value'";
                }
                $set_update = implode(", ", $set);
                $sql = "UPDATE enderecos SET $set_update WHERE id = '$id'";
            }            
            
            return $conn->query($sql);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);            
        }
    }    
}