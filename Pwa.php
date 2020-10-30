<?php declare(strict_types=1);

namespace tiFy\Plugins\Pwa;

use Exception;
use Psr\Container\ContainerInterface as Container;
use tiFy\Plugins\Pwa\Contracts\Pwa as PwaContract;
use tiFy\Plugins\Pwa\Partial\InstallPromotionPartial;
use tiFy\Routing\Strategy\AppStrategy;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Support\Proxy\Partial;
use tiFy\Support\Proxy\Url;
use tiFy\Support\Proxy\Router;
use tiFy\Support\Proxy\Storage;
use tiFy\Support\ParamsBag;

/**
 * Class Pwa
 * @package tiFy\Plugins\Pwa
 *
 * @desc Extension PresstiFy permettant de transformer son site en application web progressive (Progressive Web App).
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\Pwa
 * @version 2.0.0
 * Activation :
 * ----------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\Pwa\PwaServiceProvider à la liste des fournisseurs de services
 *     chargés automatiquement par l'application.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\Pwa\PwaServiceProvider;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          PwaServiceProvider::class
 *          ...
 *      ]
 * ];
 *
 * Configuration :
 * ----------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier pwa.php
 * @see /vendor/presstify-plugins/pwa/Resources/config/pwa.php Exemple de configuration
 */
class Pwa implements PwaContract
{
    /**
     * Instance de l'extension de gestion d'optimisation de site.
     * @var PwaContract|null
     */
    protected static $instance;

    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    protected bool $booted = false;

    /**
     * Instance de la configuration associée.
     * @var ParamsBag|null
     */
    protected ?ParamsBag $config;

    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected ?Container $container;

    /**
     * @var LocalFilesystem|null
     */
    protected ?LocalFilesystem $resources;

    /**
     * CONSTRUCTEUR.
     *
     * @param array $config
     * @param Container|null $container
     *
     * @return void
     */
    public function __construct(array $config, ?Container $container = null)
    {
        $this->setConfig($config);
        $this->setContainer($container);

        if (!self::$instance instanceof PwaContract) {
            self::$instance = $this;
        }
    }

    /**
     * @inheritDoc
     */
    public static function instance(): PwaContract
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }

        throw new Exception('Impossible de récupérer l\'instance du gestionnaire de PWA.');
    }

    /**
     * @inheritDoc
     */
    public function boot(): PwaContract
    {
        if (!$this->booted) {
            /** Routage */
            Router::setControllerStack([
                'pwa' => $this->getContainer()->get('pwa.controller')
            ]);

            Router::get('/manifest.webmanifest', ['pwa', 'manifest'])->strategy('json');

            Router::get('/offline.html', ['pwa', 'offline'])->setStrategy(new AppStrategy());

            Router::get('/sw.js', ['pwa', 'serviceWorker'])->setStrategy(new AppStrategy());
            /**/

            /** Partials */
            Partial::register('pwa-install-promotion', (new InstallPromotionPartial())->setPwa($this));
            /**/

            add_action('wp_head', function () {
                echo "<link rel=\"manifest\" href=\"" . Url::root('/manifest.webmanifest')->path() . "\">";
            }, 1);

            add_action('wp_footer', function () {
                echo partial('pwa-install-promotion');
            }, 1);

            $this->booted = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function config($key = null, $default = null)
    {
        if (!isset($this->config) || is_null($this->config)) {
            $this->config = new ParamsBag();
        }

        if (is_string($key)) {
            return $this->config->get($key, $default);
        } elseif (is_array($key)) {
            return $this->config->set($key);
        } else {
            return $this->config;
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null)
    {
        if (!isset($this->resources) ||is_null($this->resources)) {
            $this->resources = Storage::local(__DIR__ . '/Resources');
        }

        return is_null($path) ? $this->resources : $this->resources->path($path);
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $attrs): PwaContract
    {
        $this->config($attrs);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): PwaContract
    {
        $this->container = $container;

        return $this;
    }
}
