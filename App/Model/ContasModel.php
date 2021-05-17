<?php
namespace App\Model;

use Exception;
use App\Core\Transaction;
use App\Model\LoggerModel;
use App\Model\ResourceModel\ContasResourceModel;

class ContasModel
{
    private $database = 'wjcrypto';
     

    public function save($param)
    { 
        try {
            Transaction::open($this->database);
            if(!isset($param['id']))
            $param['numero_conta'] = ContasResourceModel::nextConta();
            $result = ContasResourceModel::save($param);
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
            $result= ContasResourceModel::find($id);
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();     

            return $result;
        } catch (Exception $e) {
        Transaction::rollback();
        $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $id]);
        $log->createLog();
        echo $e->getMessage();
        }
    }

    public function validaConta($numero_conta, $senha)
    {
        try {
            Transaction::open($this->database);
            $result= ContasResourceModel::findByContaSenha($numero_conta, $senha);
            Transaction::close();

            // $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            // $log->createLog();     

            return $result;
        } catch (Exception $e) {
        Transaction::rollback();
        $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => '']);
        $log->createLog();
        echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            Transaction::open($this->database);   
            $result = ContasResourceModel::delete($id);    
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();     

            return $result;
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
            $result = ContasResourceModel::all();        
            Transaction::close();

            $log = new LoggerModel('warning', __CLASS__."->".__FUNCTION__,['sql' => $result]);
            $log->createLog();   

            return $result;    
        } catch (Exception $e) {
            Transaction::rollback();
            $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__, ['result' => $e->getMessage() , 'data' => '']);
            $log->createLog();
            echo $e->getMessage();
        }
    }
}