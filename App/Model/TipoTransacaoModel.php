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
       // try {
            Transaction::open($this->database);
            //$param = CryptModel::encrypt($param);
            $result = TipoTransacaoResourceModel::save($param);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();
            
            return $result; 
    //    // } catch (Exception $e) {
    //         Transaction::rollback();
    //         $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
    //         $log->createLog();
    //         echo $e->getMessage();
    //    // }
    }

    public function edit($id)
    {
        // try {
            Transaction::open($this->database);
            $result= TipoTransacaoResourceModel::find($id);                
            Transaction::close();

            //$result = CryptModel::decrypt($result);

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();

            return $result;                
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }

    public function delete($id)
    {
        // try {
            Transaction::open($this->database);
            $result = TipoTransacaoResourceModel::delete($id);    
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();                

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
        // try {
            Transaction::open($this->database);
            $result = TipoTransacaoResourceModel::all();
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog(); 

            return $result;    
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => '']);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }
}