<?php

namespace tiFy\Plugins\Pwa\Api;

use League\Route\Strategy\JsonStrategy;
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
        router()->group(
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
     * @return array
     */
    public function root()
    {
        return ['error' => 'Paramètres manquant'];
    }

    /**
     *
     * @return array
     */
    public function addSubscriber()
    {
        return request()->all();
    }
}