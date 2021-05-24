<?php

use App\Controller\Contas;
use App\Controller\HomePage;
use App\Controller\TipoTransacao;
use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/login', [HomePage::class, 'login']);
SimpleRouter::get('/logout', [HomePage::class, 'logout']);
SimpleRouter::get('/cadastro', [HomePage::class, 'cadastro']);
//validar basic auth
SimpleRouter::post('/contas_validar', [Contas::class, 'validarConta']);
SimpleRouter::post('/contas_verificar', [Contas::class, 'verificarConta']);
//criar conta/cliente/endereco
SimpleRouter::post('/contas_cliente/create', [Contas::class, 'saveContaCliente']);//criar cliente

//exibe apenas se o usuario estiver logado
SimpleRouter::group(['middleware' => App\Api\Middlewares\CustomMiddlewareFront::class], function () {
    SimpleRouter::get('/', [HomePage::class, 'execute']);
    SimpleRouter::get('/transacao_new', [Transacoes::class, 'execute']);// criar transacao
    SimpleRouter::get('/transacao_list/{numero_conta}', [Transacoes::class, 'extract']);// listar extract conta
});

//acessa a api se estiver autenticado
SimpleRouter::group(['middleware' => App\Api\Middlewares\CustomMiddleware::class], function () {
    //Tipo transação API
    SimpleRouter::get('/tipo_transacao', [TipoTransacao::class, 'execute']);
    SimpleRouter::get('/tipo_transacao/{id}', [TipoTransacao::class, 'getById']);
    SimpleRouter::post('/tipo_transacao/create', [TipoTransacao::class, 'save']);
    SimpleRouter::delete('/tipo_transacao/{id}', [TipoTransacao::class, 'delete']);
    // Conta API
    SimpleRouter::get('/contas/{numero_conta}', [Contas::class, 'getById']);// lista as informações da conta
    SimpleRouter::post('/contas/create', [Contas::class, 'save']);//criar conta
    SimpleRouter::get('/contas_saldo/{numero_conta}', [Contas::class, 'getSaldoConta']);//buscar saldo da conta
    // Cliente API
    SimpleRouter::get('/clientes/{id}', [Clientes::class, 'getById']);// lista as informações do cliente
    SimpleRouter::post('/clientes/create', [Clientes::class, 'save']);//criar cliente
    // Endereco API
    SimpleRouter::get('/enderecos/{id}', [Enderecos::class, 'getById']);// lista as informações do enderecos
    SimpleRouter::post('/enderecos/create', [Enderecos::class, 'save']);//criar enderecos
    //Transações API
    SimpleRouter::get('/transacao', [Transacoes::class, 'list']);
    SimpleRouter::get('/transacao/{id}', [Transacoes::class, 'getById']);
    SimpleRouter::post('/transacao/create', [Transacoes::class, 'createTransacao']);
    SimpleRouter::get('/contas_transacoes/{numero_conta}', [Contas::class, 'getTransacoesByConta']);  
});