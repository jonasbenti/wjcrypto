<?php

namespace App\Model;

use Exception;

class UtilModel
{
    public static function encrypt(array $array_crypt)
    {        
        if (!empty($array_crypt)) { 
            $result = [];
            $index_not_crypt = ['id', 'created_at', 'updated_at', 'clientes_id', 'contas_id', 'tipo_transacao_id', 'conta_transferencia_id', 'ativo', 'endereco_princiapal'];
            foreach ($array_crypt as $key => $value) {
                $result[$key] = in_array($key, $index_not_crypt) ? $value : base64_encode($value);
            }

            return $result;
        } else {
            throw new Exception('Lista de dados vazia!'.__FUNCTION__);
        }
    }

    public static function decrypt(array $array_crypt)
    {
        if (!empty($array_crypt)) { 
            $result = [];
            $index_not_crypt = ['id', 'created_at', 'updated_at', 'clientes_id', 'contas_id', 'tipo_transacao_id', 'conta_transferencia_id', 'ativo', 'endereco_principal', 'credito_debito'];
            foreach ($array_crypt as $key => $value) {
                $result[$key] = in_array($key, $index_not_crypt) ? $value : base64_decode($value);
            }

            return $result;
        } else {
            throw new Exception('Lista de dados vazia!'.__FUNCTION__);
        }
    } 

    public static function authToken ()
    {
        $header = apache_request_headers();
        $header['Authorization'];
        $auth1 = explode(' ',$header['Authorization']);
         
        //  die;
        //  var_dump($_POST);
        $auth_decrypt = base64_decode($auth1[1]);
        $auth1 = explode(':',$auth_decrypt);

        // var_dump($auth1);
        // die;
        $numero_conta = $auth1[0];
        $senha = $auth1[1];

        return ['numero_conta' => $numero_conta, 'senha' => $senha];
    }
}