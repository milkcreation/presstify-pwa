<?php

namespace tiFy\Plugins\Pwa;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\Pwa\Api\PwaApi;
use tiFy\Plugins\Pwa\Manifest\Manifest;
use tiFy\Plugins\Pwa\Push\PwaPushSend;
use tiFy\Plugins\Pwa\Push\PwaPushSubscriber;
use tiFy\Plugins\Pwa\ServiceWorker\ServiceWorker;

class PwaServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'pwa',
        'pwa.api',
        'pwa.manifest',
        'pwa.push.send',
        'pwa.push.subscriber',
        'pwa.service-worker',
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        add_action('after_setup_theme', function () {
            $this->getContainer()->get('pwa.manifest');
            $this->getContainer()->get('pwa.service-worker');
        });
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerManager();
        $this->registerApi();
        $this->registerManifest();
        $this->registerPush();
        $this->registerServiceWorker();
    }

    /**
     * Déclaration du contrôleur de l'api.
     *
     * @return void
     */
    public function registerApi()
    {
        $this->getContainer()->share('pwa.api', PwaApi::class);
    }

    /**
     * Déclaration du contrôleur principal.
     *
     * @return void
     */
    public function registerManager()
    {
        $this->getContainer()->share('pwa', PwaManager::class);
    }

    /**
     * Déclaration du contrôleur de manifest PWA.
     *
     * @return void
     */
    public function registerManifest()
    {
        $this->getContainer()->share('pwa.manifest', Manifest::class);
    }

    /**
     * Déclaration des contrôleurs de notifications push.
     *
     * @return void
     */
    public function registerPush()
    {
        $this->getContainer()->share('pwa.push.send', PwaPushSend::class);

        $this->getContainer()->share('pwa.push.subscriber', function () {
            return new PwaPushSubscriber(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
                DB_USER,
                DB_PASSWORD
            );
        });
    }

    /**
     * Déclaration du contrôleur de service worker.
     *
     * @return void
     */
    public function registerServiceWorker()
    {
        $this->getContainer()->share('pwa.service-worker', ServiceWorker::class);
    }
}