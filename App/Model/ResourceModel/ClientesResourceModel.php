<?php

namespace App\Model\ResourceModel;

use PDO;
use Exception;
use App\Core\Transaction;

class ClientesResourceModel
{
    public static function find($id)
    {
        if ($conn = Transaction::get()) {            
            $result = $conn->prepare("select * from clientes WHERE id= :id");
            $result->execute([':id' => $id]);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function delete($id)
    {
        if ($conn = Transaction::get()) {
            $sql = "DELETE from clientes WHERE id= :id";
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
            $result = $conn->query("select * from clientes ORDER BY id desc");

            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);
        }
    }

    public static function save($clientes)
    {
        if ($conn = Transaction::get()) {
              
            foreach ($clientes as $key => $value) {
                $clientes[$key] = strip_tags(addslashes($value));
            }
            $id = isset($clientes['id']) ? $clientes['id'] : 0;
            unset($clientes['id']);

            if (empty($id)) {
                $keys_insert = implode(", ",array_keys($clientes));
                $values_insert = "'".implode("', '",array_values($clientes))."'";
                $sql = "INSERT INTO clientes ($keys_insert) VALUES ($values_insert)";

            } else {
                $set = [];
                foreach ($clientes as $column => $value) {
                    $set[] = "$column = '$value'";
                }
                $set_update = implode(", ", $set);
                $sql = "UPDATE clientes SET $set_update WHERE id = '$id'";
            }

            return $conn->query($sql);
        } else {
            throw new Exception('Não há transação ativa!!'.__FUNCTION__);

        }
    }    
}