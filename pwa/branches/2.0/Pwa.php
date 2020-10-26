<?php declare(strict_types=1);

namespace tiFy\Plugins\Pwa;

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
final class Pwa
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
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
