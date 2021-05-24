<?php
namespace App\Model;

use Exception;
use App\Core\Transaction;
use App\Model\LoggerModel;
use App\Model\ResourceModel\ContasResourceModel;
use App\Model\ResourceModel\ClientesResourceModel;
use App\Model\ResourceModel\EnderecosResourceModel;

class ContasClienteModel
{
    private $database = 'wjcrypto';     

    public function save($param)
    { 
        //try {
            //alimenta cada entidade com seus respectivos dados
            $dados_cliente = [];
            $dados_endereco = [];
            $dados_conta = [];

            foreach ($param as $key => $value) {
                $subkey = substr($key, 0, 4);
                $endkey = substr($key, 4);
                if ($subkey == "cli_") {
                    $dados_cliente[$endkey] = $value;
                } else if ($subkey == "end_") {
                    $dados_endereco[$endkey] = $value;
                }else {
                    $dados_conta[$endkey] = $value;
                }                
            }
            Transaction::open($this->database);

            //primeiro inserir cadastro de cliente
            $dados_cliente = UtilModel::encrypt($dados_cliente);
            $result_cli = ClientesResourceModel::save($dados_cliente);
            
            $cliente = ClientesResourceModel::findByCpfCnpj($dados_cliente['cpf_cnpj']);
            //valida se o cliente foi inserido
            if(empty($cliente)) throw new Exception("Não foi possivel inserir dados do cliente: $result_cli", 1);
            //popula novas entidades com o clientes_id
            $dados_endereco['clientes_id'] = $cliente['id'];
            $dados_conta['clientes_id'] = $cliente['id'];

            $dados_endereco = UtilModel::encrypt($dados_endereco);
            $dados_conta = UtilModel::encrypt($dados_conta);

            //insere os dados de endereco
            EnderecosResourceModel::save($dados_endereco);
            $endereco = EnderecosResourceModel::findByclienteByDesc($dados_endereco['clientes_id'], $dados_endereco['descricao']);
            //valida se o endereço foi inserido
            if(empty($endereco)) throw new Exception("Não foi possivel inserir dados de endereço", 1);
 
            // die(var_dump($dados_conta));
            //insere os dados da conta
            if(!isset($dados_conta['id'])) $dados_conta['numero_conta'] = ContasResourceModel::nextConta();
            //die(var_dump($dados_conta));
            ContasResourceModel::save($dados_conta);
            $conta = ContasResourceModel::findByContaSenha($dados_conta['numero_conta'], $dados_conta['senha']);
            if(empty($conta)) throw new Exception("Não foi possivel inserir dados da conta", 1);
            Transaction::close();

            return "Parabéns! Conta numero {$dados_conta['numero_conta']} cadastrada com sucesso";
        
        // } catch (Exception $e) {
        //     Transaction::rollback();
        //     $log = new LoggerModel('error', __CLASS__."->".__FUNCTION__,['result' => $e->getMessage() , 'data' => $param]);
        //     $log->createLog();
        //     echo $e->getMessage();
        // }
    }

}