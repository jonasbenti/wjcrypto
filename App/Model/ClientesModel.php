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
        try {
            Transaction::open($this->database);
            $result = ClientesResourceModel::save($param);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();
            
            return $result;    
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
            $log->createLog();
            echo $e->getMessage();
        }
    }

    public function edit($id)
    {
        try {
            Transaction::open($this->database);
            $result= ClientesResourceModel::find($id);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['msg' => $result]);
            $log->createLog();
            
            return $result;
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
            $log->createLog();
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            Transaction::open($this->database);
            $result = ClientesResourceModel::delete($id);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();

            return json_encode($result);
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
            $log->createLog();
            echo $e->getMessage();
        }
    }

    public function load()
    {
        try {
            Transaction::open($this->database);
            $result = ClientesResourceModel::all();
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();

            return json_encode($result);
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => '']);
            $log->createLog();
            echo $e->getMessage();
        }
    }
}