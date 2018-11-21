<?php

namespace tiFy\Plugins\Pwa;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Container\ContainerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\RouteCollection;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use tiFy\Plugins\Pwa\Contracts\PwaManager;
use tiFy\Plugins\Pwa\Push\PwaPushSend;
use tiFy\Plugins\Pwa\Push\PwaPushSubscriber;
use Zend\Diactoros\Response\SapiEmitter;

class PwaServiceProvider extends AbstractServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'pwa.api',
        'pwa.db',
        'pwa.http.request',
        'pwa.http.zend.emitter',
        'pwa.http.zend.request',
        'pwa.http.zend.response',
        'pwa.push.send',
        'pwa.push.subscriber',
        'pwa.router'
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {

    }

    /**
     * {@inheritdoc}
     *
     * @return PwaManager|ContainerInterface
     */
    public function getContainer()
    {
        return parent::getContainer();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->registerHttp();
        $this->registerPush();
        $this->registerRouter();
    }

    /**
     * Déclaration du contrôleur de traitement des requêtes HTTP.
     *
     * @return void
     */
    public function registerHttp()
    {
        $this->getContainer()->share('pwa.http.request', function () {
            return Request::createFromGlobals();
        });

        $this->getContainer()->share('pwa.http.zend.emitter', new SapiEmitter());

        $this->getContainer()->share('pwa.http.zend.request', function () {
            return (new DiactorosFactory())->createRequest($this->getContainer()->get('pwa.http.request'));
        });

        $this->getContainer()->share('pwa.http.zend.response', function () {
            return (new DiactorosFactory())->createResponse(new Response());
        });
    }

    /**
     * Déclaration des contrôleurs de notifications push.
     *
     * @return void
     */
    public function registerPush()
    {
        $this->getContainer()->share(
            'pwa.push.send',
            new PwaPushSend($this->getContainer())
        );

        $this->getContainer()->share(
            'pwa.push.subscriber',
            new PwaPushSubscriber(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
                DB_USER,
                DB_PASSWORD
            )
        );
    }

    /**
     * Déclaration du contrôleur de routage.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->getContainer()->share('pwa.api', new PwaApi($this->getContainer()));
        $this->getContainer()->share('pwa.router', new RouteCollection($this->getContainer()));
    }
}