<?php

namespace tiFy\Plugins\Pwa\Api;

use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Plugins\Pwa\Contracts\PwaManager;

class PwaApi
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __invoke()
    {
        $this->router()->group(
            '/pwa/api',
            function(\League\Route\RouteGroup $router) {
                $router->map('GET', '/', [$this, 'root']);
                $router->map('GET', '/subscriber', [$this, 'addSubscriber']);
            }
        )
            ->setStrategy(new JsonStrategy);
    }

    /**
     *
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function root(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode(['error' => 'Paramètres manquant']));

        return $response->withStatus(400);
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function addSubscriber(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode(request()->all()));

        return $response->withStatus(200);
    }
}