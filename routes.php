<?php

use App\Controller\HomePage;
use App\Controller\TipoTransacao;
use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/', [HomePage::class, 'execute']);
SimpleRouter::post('/validar', [HomePage::class, 'validarLogin']);
SimpleRouter::post('/verificar', [HomePage::class, 'verificarLogin']);
SimpleRouter::get('/login', [HomePage::class, 'login']);

//Tipo transação API
SimpleRouter::get('/tipo_transacao', [TipoTransacao::class, 'execute']);
SimpleRouter::get('/tipo_transacao/{id}', [TipoTransacao::class, 'getById']);
SimpleRouter::post('/tipo_transacao/create', [TipoTransacao::class, 'save']);
SimpleRouter::delete('/tipo_transacao/{id}', [TipoTransacao::class, 'delete']);

// Conta API
//SimpleRouter::get('/contas', [Contas::class, 'execute']);// lista as informações da conta
SimpleRouter::get('/contas/{numero_conta}', [Contas::class, 'getById']);// lista as informações da conta
SimpleRouter::get('/contas_transacoes/{numero_conta}', [Contas::class, 'getTransacoesByConta']);// lista as informações da conta
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
//SimpleRouter::get('/transacao_list', [Transacoes::class, 'list']);

//Transaçoes APP
SimpleRouter::get('/transacao_new', [Transacoes::class, 'execute']);// criar transacao
SimpleRouter::get('/transacao_list', [Transacoes::class, 'extract']);// listar extract


// SimpleRouter::group(['middleware' => \Demo\Middleware\Auth::class], function () {
//     SimpleRouter::get('/', function ()    {
//         // Uses Auth Middleware
//     });

//     SimpleRouter::get('/user/profile', function () {
//         // Uses Auth Middleware
//     });
// });