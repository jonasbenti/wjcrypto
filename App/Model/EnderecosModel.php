<?php

namespace App\Model;

use Exception;
use App\Core\Transaction;
use App\Model\LoggerModel;
use App\Model\ResourceModel\EnderecosResourceModel;

class EnderecosModel
{
    private $database = 'wjcrypto';
     
    public function save($param)
    { 
        // try {
            Transaction::open($this->database);
            $param = UtilModel::encrypt($param);
            $result = EnderecosResourceModel::save($param);
            Transaction::close();

            return $result;
            
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }

    public function edit($id)
    {
        //try {
            Transaction::open($this->database);
            $result= EnderecosResourceModel::find($id);
            Transaction::close();
            $result = UtilModel::decrypt($result);

            return $result;
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }

    public function load()
    {
        //try {
            Transaction::open($this->database);
            $result = EnderecosResourceModel::all();
            Transaction::close();

            return $result;
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => '']);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }
}