<?php

/**
 * @name Pwa
 * @desc Progressive Web App Manager.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstify-plugins/pwa
 * @namespace \tiFy\Plugins\Pwa
 * @version 1.2.0
 */
namespace tiFy\Plugins\Pwa;

use League\Container\Container;
use tiFy\Plugins\Pwa\Contracts\PwaManager as PwaManagerContract;

/**
 * Class Pwa
 * @package tiFy\Plugins\Pwa
 *
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
final class Pwa
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
        $this->app = $this;

        return;

        if (request()->get('push') === 'send') :
            app('pwa.push.send');
        endif;

        app('pwa.api');

        try {
            $this->routerEmitter()->emit($this->routerResponse());
        } catch (\Exception $e) {

        }
    }
}
