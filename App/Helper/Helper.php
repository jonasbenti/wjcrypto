<?php

namespace App\Helper;

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;

class Helper
{
        /**
     * Get url for a route by using either name/alias, class or method name.
     *
     * The name parameter supports the following values:
     * - Route name
     * - Controller/resource name (with or without method)
     * - Controller class name
     *
     * When searching for controller/resource by name, you can use this syntax "route.name@method".
     * You can also use the same syntax when searching for a specific controller-class "MyController@home".
     * If no arguments is specified, it will return the url for the current loaded route.
     *
     * @param string|null $name
     * @param string|array|null $parameters
     * @param array|null $getParams
     * @return \Pecee\Http\Url
     * @throws \InvalidArgumentException
     */
    public function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
    {
        return Router::getUrl($name, $parameters, $getParams);
    }

    /**
     * @return \Pecee\Http\Response
     */
    public function response(): Response
    {
        return Router::response();
    }

    /**
     * @return \Pecee\Http\Request
     */
    public function request(): Request
    {
        return Router::request();
    }

    /**
     * Get input class
     * @param string|null $index Parameter index name
     * @param string|mixed|null $defaultValue Default return value
     * @param array ...$methods Default methods
     * @return \Pecee\Http\Input\InputHandler|array|string|null
     */
    public function input($index = null, $defaultValue = null, ...$methods)
    {
        if ($index !== null) {
            return $this->request()->getInputHandler()->value($index, $defaultValue, ...$methods);
        }

        return $this->request()->getInputHandler();
    }

    /**
     * @param string $url
     * @param int|null $code
     */
    public function redirect(string $url, ?int $code = null): void
    {
        if ($code !== null) {
            $this->response()->httpCode($code);
        }

        $this->response()->redirect($url);
    }

    /**
     * Get current csrf-token
     * @return string|null
     */
    public function csrf_token(): ?string
    {
        $baseVerifier = Router::router()->getCsrfVerifier();
        if ($baseVerifier !== null) {
            return $baseVerifier->getTokenProvider()->getToken();
        }

        return null;
    }
}
