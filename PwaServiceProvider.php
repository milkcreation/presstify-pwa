<?php declare(strict_types=1);

namespace tiFy\Plugins\Pwa;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\Pwa\Api\PwaApi;
use tiFy\Plugins\Pwa\Push\PwaPushSend;
use tiFy\Plugins\Pwa\Partial\InstallPromotionPartial;
use tiFy\Support\Proxy\Partial;
//use tiFy\Plugins\Pwa\Push\PwaPushSubscriber;

class PwaServiceProvider extends ServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        'pwa',
        'pwa.api',
        'pwa.controller',
        'pwa.push.send',
        'pwa.push.subscriber'
    ];

    /**
     * @inheritDoc
     */
    public function boot()
    {
        if (($wp = $this->getContainer()->get('wp')) && $wp->is()) {
            add_action('after_setup_theme', function () {
	            $this->getContainer()->get('pwa')->boot();
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('pwa', function () {
            return new Pwa(config('pwa', []), $this->getContainer());
        });

        $this->getContainer()->share('pwa.api', function () {
            return new PwaApi();
        });

        $this->getContainer()->share('pwa.controller', function () {
            /** @var Pwa $manager */
            $manager = $this->getContainer()->get('pwa');

            $provider = $manager->provider('controller');
            if (!is_object($provider)) {
                $provider = new $provider;
            }

            $provider = $provider instanceof PwaController ? $provider : new PwaController();

            return $provider->setPwa($manager);
        });

        $this->getContainer()->share('pwa.push.send', function () {
            return new PwaPushSend();
        });

        /* * /
        $this->getContainer()->share('pwa.push.subscriber', function () {
            return new PwaPushSubscriber();
        });
        /**/
    }
}