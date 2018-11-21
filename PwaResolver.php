<?php

namespace tiFy\Plugins\Pwa;

use Illuminate\Http\Request;
use League\Route\RouteCollection;
use \Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;

trait PwaResolver
{
    /**
     * Instance du contrôleur de l'application.
     * @var PwaManager
     */
    protected $app;

    /**
     * Instance du controleur de requête HTTP.
     *
     * @return Request
     */
    public function request()
    {
        return $this->app->get('pwa.http.request');
    }

    /**
     * Instance du controleur de routage.
     *
     * @return RouteCollection
     */
    public function router()
    {
        return $this->app->get('pwa.router');
    }

    /**
     * Instance de la sortie d'affichage du routage.
     *
     * @return SapiEmitter
     */
    public function routerEmitter()
    {
        return $this->get('pwa.http.zend.emitter');
    }

    /**
     * Instance de la réponse du routage.
     *
     * @return ResponseInterface
     */
    public function routerResponse()
    {
        return $this->router()->dispatch(
            $this->app->get('pwa.http.zend.request'),
            $this->app->get('pwa.http.zend.response')
        );
    }
}