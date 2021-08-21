<?php

namespace Frankenstein;

use Frankenstein\Http\Controller;
use Frankenstein\Http\Request;
use Frankenstein\Http\Response;

class Application
{
    public Request $request;
    public Router $router;
    public Response $response;
    public ?Controller $controller;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request);
    }

    public function run()
    {
        try {
            $callback = $this->router->getRoute();

            if (!$callback) {
                echo $this->response->json([
                    'error' => 'Not found',
                ], 404);
                die();
            }

            if (is_array($callback)) {
                /**
                 * @var \Lib\Controller controller
                 */
                $controller = new $callback[0];
                $controller->action = $callback[1];
                $this->controller = $controller;
                $callback[0] = $controller;
            }

            echo call_user_func($callback, $this->request, $this->response, ...$this->router->getParams());
        } catch (\Throwable $th) {
            echo $this->response->json([
                'exception' => get_class($th),
                'error' => $th->getMessage(),
            ], 500);
            die();
        }
    }
}
