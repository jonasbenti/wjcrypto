<?php

namespace App\Model;

use Exception;
use App\Core\Transaction;
use App\Model\CryptModel;
use App\Model\LoggerModel;
use App\Model\ResourceModel\TipoTransacaoResourceModel;

class TipoTransacaoModel
{
    private $database = 'wjcrypto';

    public function save($param)
    { 
        Transaction::open($this->database);
        $result = TipoTransacaoResourceModel::save($param);
        Transaction::close();
        
        return $result; 
    }

    public function edit($id)
    {
        Transaction::open($this->database);
        $result= TipoTransacaoResourceModel::find($id);                
        Transaction::close();

        return $result;
    }

    public function load()
    {
        Transaction::open($this->database);
        $result = TipoTransacaoResourceModel::all();
        Transaction::close();

        return $result;    
    }
}