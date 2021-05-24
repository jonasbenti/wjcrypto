<?php

namespace App\Api\Middlewares;

use Pecee\Http\Request;
use App\Model\UtilModel;
use Pecee\Http\Middleware\IMiddleware;

class CustomMiddleware implements IMiddleware {

    public function handle(Request $request) : void
    {
        $result = UtilModel::authToken();
        if($result['code'] == 401)
        {
            echo(json_encode($result));
            die;
        }
    }
}