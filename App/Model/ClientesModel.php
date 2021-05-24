<?php

namespace App\Model;

use Exception;
use App\Core\Transaction;
use App\Model\LoggerModel;
use App\Model\ResourceModel\ClientesResourceModel;

class ClientesModel
{
    private $database = 'wjcrypto';     

    public function save($param)
    { 
        //try {
            Transaction::open($this->database);
            $param = UtilModel::encrypt($param);
            $result = ClientesResourceModel::save($param);
            Transaction::close();

            return $result;    
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
            
        //     echo $e->getMessage();
        // }
    }

    public function edit($id)
    {
        //try {
            Transaction::open($this->database);
            $result= ClientesResourceModel::find($id);
            Transaction::close();
            $result = UtilModel::decrypt($result);

            return $result;
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
            
        //     echo $e->getMessage();
        // }
    }

    public function load()
    {
        //try {
            Transaction::open($this->database);
            $result = ClientesResourceModel::all();
            Transaction::close();

            return json_encode($result);
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => '']);
            
        //     echo $e->getMessage();
        // }
    }
}