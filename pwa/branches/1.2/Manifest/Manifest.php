<?php declare(strict_types=1);

namespace tiFy\Plugins\Pwa\Manifest;

use tiFy\Plugins\Pwa\Contracts\PwaManager;

class Manifest
{
    /**
     * CONSTRUCTEUR.
     *
     * @param PwaManager $manager Instance du controleur de gestion PWA.
     *
     * @return void
     */
    public function __construct()
    {
        router()->get('/cftc-boulanger.fr/manifest.json', function() {
            return [
                'name'             => 'CFTC Boulanger',
                'short_name'       => 'CFTC Boulanger',
                'icons'            => [
                    [
                        'src'   => get_template_directory_uri() . '/dist/images/manifest/128.png',
                        'sizes' => '128x128',
                        'type'  => 'image/png',
                    ],
                    [
                        'src'   => get_template_directory_uri() . '/dist/images/manifest/144.png',
                        'sizes' => '144x144',
                        'type'  => 'image/png',
                    ],
                    [
                        'src'   => get_template_directory_uri() . '/dist/images/manifest/152.png',
                        'sizes' => '152x152',
                        'type'  => 'image/png',
                    ],
                    [
                        'src'   => get_template_directory_uri() . '/dist/images/manifest/192.png',
                        'sizes' => '192x192',
                        'type'  => 'image/png',
                    ],
                    [
                        'src'   => get_template_directory_uri() . '/dist/images/manifest/256.png',
                        'sizes' => '256x256',
                        'type'  => 'image/png',
                    ],
                    [
                        'src'   => get_template_directory_uri() . '/dist/images/manifest/512.png',
                        'sizes' => '512x512',
                        'type'  => 'image/png',
                    ]
                ],
                'start_url'        => home_url('/index.php'),
                'scope'            => '/cftc-boulanger.fr/',
                'display'          => 'standalone',
                'background_color' => '#0077B8',
                'theme_color'      => '#FFFFFF',
            ];
        })->setStrategy(app()->get('router.strategy.json'));

        add_action('wp_head', function () {
            echo "<!-- Manifest PWA -->";
            echo "<link rel=\"manifest\" href=\"https://dev.tigreblanc.fr/cftc-boulanger.fr/manifest.json\">";
        }, 0);
    }
}