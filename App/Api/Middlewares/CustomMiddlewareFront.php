<?php

namespace App\Api\Middlewares;

use Pecee\Http\Request;
use Pecee\Http\Middleware\IMiddleware;

class CustomMiddlewareFront implements IMiddleware {

    public function handle(Request $request) : void
    {
        if (!(isset($_SESSION['auth']) && !empty($_SESSION['auth']) && !empty($_SESSION['numero_conta']))) {
           $html = file_get_contents('html/nao_logado.html');
           echo $html;
           die;
        }
    }
}