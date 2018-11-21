<?php

namespace tiFy\Plugins\Pwa;

use League\Container\Container;
use tiFy\Plugins\Pwa\Contracts\PwaManager as PwaManagerContract;

class PwaManager extends Container implements PwaManagerContract
{
    use PwaResolver;

    /**
     * Instance du contrôleur du gestionnaire de ressources.
     * @var PwaManagerContract
     */
    protected $app;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->app = $this;

        $provider = new PwaServiceProvider();
        $this->addServiceProvider($provider);

        if ($this->request()->get('push') === 'send') :
            $this->get('pwa.push.send');
        endif;

        $this->app->get('pwa.api');

        try {
            $this->routerEmitter()->emit($this->routerResponse());
        } catch (\Exception $e) {

        }
    }
}