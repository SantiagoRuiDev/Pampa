<?php

namespace Router;

class Response
{
    /**
     * Resolve the request and send the definitive response.
     *
     * @param mixed $data The data to be sent
     * @param mixed $status The status of the response
     * @return void
     */
    public function send($data, $status): void
    {
        header("Content-Type: application/json");
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        echo json_encode($data);
    }

    /**
     * Check if the inserted status is valid.
     *
     * @param int $code The HTTP status code
     * @return string
     */
    private function _requestStatus(int $code): string
    {
        $status = array(
            100 => "Continue",
            101 => "Switching Protocols",
            200 => "OK",
            201 => "Created",
            202 => "Accepted",
            203 => "Non-Authoritative Information",
            204 => "No Content",
            205 => "Reset Content",
            206 => "Partial Content",
            300 => "Multiple Choices",
            301 => "Moved Permanently",
            302 => "Found",
            303 => "See Other",
            304 => "Not Modified",
            305 => "Use Proxy",
            307 => "Temporary Redirect",
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            407 => "Proxy Authentication Required",
            408 => "Request Timeout",
            409 => "Conflict",
            410 => "Gone",
            411 => "Length Required",
            412 => "Precondition Failed",
            413 => "Request Entity Too Large",
            414 => "Request-URI Too Long",
            415 => "Unsupported Media Type",
            416 => "Requested Range Not Satisfiable",
            417 => "Expectation Failed",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeout",
            505 => "HTTP Version Not Supported"
        );
        return (isset($status[$code])) ? $status[$code] : $status[500];
    }
}

class Request
{

    private mixed $body;
    private mixed $files;
    private array $params;
    private array $headers;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];
        $this->body = json_decode(file_get_contents("php://input"));
        $this->files = $_FILES;
    }

    /**
     * Get body from HTTP Request
     *
     * @return mixed
     */
    public function getBody(): mixed
    {
        return $this->body;
    }

    
    /**
     * Get files from HTTP Request
     *
     * @return mixed
     */
    public function getFiles(): mixed
    {
        return $this->files;
    }


    /**
     * Get params from HTTP Request
     *
     * @return mixed
     */
    public function getParams(): mixed
    {
        return $this->params;
    }

    /**
     * Get headers from HTTP Request
     *
     * @return mixed
     */
    public function getHeaders(): mixed
    {
        return $this->headers;
    }

    /**
     * Execute the next function after middleware
     *
     * @return bool
     */
    public function next(): bool
    {
        return true;
    }
}


class Route
{
    private string $url;
    private string $verb;
    private string $controller;
    private string $method;
    private array $params;
    private string $middleware;

    public function __construct(string $url, string $verb, string $controller, string $method, string $middleware)
    {
        $this->url = $url;
        $this->verb = $verb;
        $this->controller = $controller;
        $this->middleware = $middleware;
        $this->method = $method;
        $this->params = [];
    }

    
    public function match(string $url, string $verb): bool
    {
        if ($this->verb != $verb) {
            return false;
        }

        $partsURL = explode("/", trim($url, '/'));
        $partsRoute = explode("/", trim($this->url, '/'));


        if (count($partsRoute) != count($partsURL)) {
            return false;
        }

        foreach ($partsRoute as $key => $part) {
            if ($part[0] != ":") {
                if ($part != $partsURL[$key])
                    return false;
            } //es un parametro
            else
                $this->params[$part] = $partsURL[$key];
        }
        return true;
    }


    public function run(): void
    {
        $controllerClass = "App\\Controller\\" . $this->controller;
        $method = $this->method;
        $params = $this->params;
        $request = new Request($params);
        $response = new Response();
        $middlewareClass = '';

        if (trim($this->middleware) != '') {
            $middlewareClass = "App\\Middleware\\" . $this->middleware;

            if ((new $middlewareClass($request))->handle($request, $response)) {
                (new $controllerClass($request))->$method($request, $response);
            }
        } else {
            (new $controllerClass($request))->$method($request, $response);
        }
    }
}

class Router
{
    private array $routeTable = [];
    private ?Route $defaultRoute;

    public function __construct()
    {
        $this->defaultRoute = null;
    }

    public function route(string $url, string $verb): void
    {
        //$ruta->url //no compila!
        foreach ($this->routeTable as $route) {
            if ($route->match($url, $verb)) {
                //TODO: ejecutar el controller//ejecutar el controller
                // pasarle los parametros
                $route->run();
                return;
            }
        }
        //Si ninguna ruta coincide con el pedido y se configuró ruta por defecto.
        if ($this->defaultRoute != null)
            $this->defaultRoute->run();
    }

    public function addRoute(string $url, string $verb, string $controller, string $method, string $middleware): void
    {
        $this->routeTable[] = new Route($url, $verb, $controller, $method, $middleware);
    }

    public function setDefaultRoute(string $controller, string $method, string $middleware): void
    {
        $this->defaultRoute = new Route("", "", $controller, $method, $middleware);
    }
}
