<?php

namespace tiFy\Plugins\Pwa;

use tiFy\Contracts\App\AppInterface;
use tiFy\App\Container\AppServiceProvider;
use tiFy\Plugins\Pwa\Contracts\PwaManager;
use tiFy\Plugins\Pwa\Api\PwaApi;
use tiFy\Plugins\Pwa\Push\PwaPushSend;
use tiFy\Plugins\Pwa\Push\PwaPushSubscriber;

class PwaServiceProvider extends AppServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'pwa',
        'pwa.api',
        'pwa.push.send',
        'pwa.push.subscriber',
        'pwa.router'
    ];

    /**
     * CONSTRUCTEUR.
     *
     * @param AppInterface $app Classe de rappel du controleur de l'application.
     *
     * @return void
     */
    public function __construct(AppInterface $app)
    {
        parent::__construct($app);

        add_action('after_setup_tify', function () {
            $this->getContainer()->get('pwa');
        });
    }

    /**
     * {@inheritdoc}
     */
    public function _boot()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->getContainer()->share('pwa', function() {return new Pwa();});
        $this->registerApi();
        $this->registerPush();
    }

    /**
     * Déclaration du contrôleur de routage.
     *
     * @return void
     */
    public function registerApi()
    {
        $this->getContainer()->share(
            'pwa.api',
            function () {
                new PwaApi();
            }
        );
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
            function () {
                new PwaPushSend();
            }
        );

        $this->getContainer()->share(
            'pwa.push.subscriber',
            function () {
                return new PwaPushSubscriber(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
                    DB_USER,
                    DB_PASSWORD
                );
            }
        );
    }
}