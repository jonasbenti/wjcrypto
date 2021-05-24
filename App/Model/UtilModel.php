<?php

namespace App\Model;

use Exception;

class UtilModel
{
    public static function encrypt(array $array_crypt)
    {        
        // if (!empty($array_crypt)) { 
            $result = [];
            $index_not_crypt = ['id', 'created_at', 'updated_at', 'clientes_id', 'contas_id', 'tipo_transacao_id', 'conta_transferencia_id', 'ativo', 'endereco_principal', 'numero_conta', 'numero_transacao'];
            foreach ($array_crypt as $key => $value) {
                $result[$key] = in_array($key, $index_not_crypt) ? $value : base64_encode($value);
            }

            return $result;
        // } else {
        //     throw new Exception('Lista de dados vazia!'.__FUNCTION__);
        // }
    }

    // public static function decrypt(array $array_crypt)
    // {
    //     // if (!empty($array_crypt)) { 
    //         $total = count($array_crypt);    
    //         return array_key_first($array_crypt);
    //         $array = [];
    //         $index_not_crypt = ['id', 'created_at', 'updated_at', 'clientes_id', 'contas_id', 'tipo_transacao_id', 'conta_transferencia_id', 'ativo', 'endereco_principal', 'numero_conta', 'numero_transacao'];
    //         if($total > 1)
    //         {
    //             foreach ($array_crypt as $item) {
    //                 $result = [];
    //                 foreach ($item as $key => $value) {
    //                     //if(!is_string($value)) die("$key: $value");
    //                     $result[$key] = in_array($key, $index_not_crypt) ? $value : base64_decode($value);
    //                 }
    //                 $array[] = $result;
    //             }                
    //         }  
    //         return $array;
    //     // } else {
    //     //     throw new Exception('Lista de dados vazia!'.__FUNCTION__);
    //     // }
    // } 

    public static function decrypt(array $array_crypt)
    {
        $index_not_crypt = ['id', 'created_at', 'updated_at', 'clientes_id', 'contas_id', 'tipo_transacao_id', 'conta_transferencia_id', 'ativo', 'endereco_principal', 'numero_conta', 'numero_transacao'];
        $array_r = [];
        // function last_array(array $array, $index_not_crypt)
        // {
        //     foreach ($array as $key => $value) {                
        //         $result[$key] = in_array($key, $index_not_crypt) ? $value : base64_decode($value);
        //     }
        //     return $result;
        // }

        foreach ($array_crypt as $item) 
        {
            if(is_array($item)) {                
                foreach ($item as $key => $value) {                
                    $result[$key] = in_array($key, $index_not_crypt) ? $value : base64_decode($value);
                }
                $array_r[] = $result;
            } else {
                foreach ($array_crypt as $key => $value) {                
                    $array_r[$key] = in_array($key, $index_not_crypt) ? $value : base64_decode($value);
                }
                break;
            } 
        }    
        return $array_r;
    } 

    public static function authToken ()
    {
        $header = apache_request_headers();
        $array_error = ["message" => "Não autorizado", "code" => 401];
   
        if(isset($header['Authorization'])) {
            $auth = explode(' ', $header['Authorization']);
            
            $auth_decrypt = base64_decode($auth[1]);
            $auth1 = explode(':',$auth_decrypt);
            $conta = new ContasModel();
            $numero_conta = $auth1[0];
            $senha = $auth1[1];

            $conta_wj = $conta->validaConta($numero_conta, $senha);  
            
            if (empty($conta_wj)) return $array_error;

            return ['numero_conta' => $conta_wj['numero_conta'], 'senha' => $conta_wj['senha'], 'id' => $conta_wj['id'], "code" => 200];
        } else {
            return $array_error;
        }      
    }    
}