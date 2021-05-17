<?php

use App\Controller\HomePage;
use App\Controller\TipoTransacao;
use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/', [HomePage::class, 'execute']);
SimpleRouter::post('/validar', [HomePage::class, 'validarLogin']);
SimpleRouter::get('/login', [HomePage::class, 'login']);

//Tipo transação
SimpleRouter::get('/tipo_transacao', [TipoTransacao::class, 'execute']);
SimpleRouter::get('/tipo_transacao/{id}', [TipoTransacao::class, 'getById']);
SimpleRouter::post('/tipo_transacao/create', [TipoTransacao::class, 'save']);
SimpleRouter::delete('/tipo_transacao/{id}', [TipoTransacao::class, 'delete']);

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