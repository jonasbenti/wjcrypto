<?php

use App\Controller\HomePage;
use App\Controller\TipoTransacao;
use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/', [HomePage::class, 'execute']);

//Tipo transação
SimpleRouter::get('/tipo_transacao', [TipoTransacao::class, 'execute']);
SimpleRouter::get('/tipo_transacao/{id}', [TipoTransacao::class, 'getById']);
SimpleRouter::post('/tipo_transacao/create', [TipoTransacao::class, 'save']);
SimpleRouter::delete('/tipo_transacao/{id}', [TipoTransacao::class, 'delete']);

//Transações
SimpleRouter::get('/transacao', [Transacoes::class, 'execute']);
SimpleRouter::get('/transacao/{id}', [Transacoes::class, 'getById']);
SimpleRouter::post('/transacao/create', [Transacoes::class, 'createTransacao']);