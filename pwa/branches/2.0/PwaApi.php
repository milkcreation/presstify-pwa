<?php

namespace tiFy\Plugins\Pwa;

use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use tiFy\Plugins\Pwa\Contracts\PwaManager;

class PwaApi
{
    use PwaResolver;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct(PwaManager $app)
    {
        $this->app = $app;
    }

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __invoke()
    {
        $this->router()->group(
            '/pwapush/api',
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function root(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode(['error' => 'Paramètres manquant']));

        return $response->withStatus(400);
    }

    /**
     *
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function addSubscriber(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode($this->request()->all()));

        return $response->withStatus(200);
    }
}