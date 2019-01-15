<?php

namespace tiFy\Plugins\Pwa;

use tiFy\Plugins\Pwa\Contracts\PwaManager as PwaManagerContract;

/**
 * Class PwaManager
 *
 * @desc Progressive Web App Manager.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\Pwa
 * @version 1.2.0
 */
final class PwaManager implements PwaManagerContract
{
    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        /*if (request()->get('push') === 'send') :
            $this->get('pwa.push.send');
        endif;*/
    }
}
