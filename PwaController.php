<?php declare(strict_types=1);

namespace tiFy\Plugins\Pwa;

use tiFy\Contracts\Http\Response;
use tiFy\Contracts\View\Engine;
use tiFy\Routing\BaseController;
use tiFy\Support\Proxy\Partial;
use tiFy\Support\Proxy\Url;
use tiFy\Support\Proxy\View;

class PwaController extends BaseController
{
    use PwaAwareTrait;

    /**
     * Manifest
     *
     * @return array
     */
    public function manifest(): array
    {
        return [
            "name"                 => get_bloginfo('name'),
            "short_name"           => get_bloginfo('name'),
            "icons"                => [
                [
                    "src"     => Url::root($this->pwa()->resources()->rel("assets/images/192.png"))->path(),
                    "sizes"   => "192x192",
                    "type"    => "image/png",
                    "purpose" => "any maskable"
                ],
                [
                    "src"     => Url::root($this->pwa()->resources()->rel("assets/images/512.png"))->path(),
                    "sizes"   => "512x512",
                    "type"    => "image/png",
                    "purpose" => "any maskable"
                ],
            ],
            "scope"                => Url::scope(),
            "start_url"            => Url::root('/')->path() . "?utm_medium=PWA&utm_source=standalone",
            "display"              => "standalone",
            "background_color"     => "#5A0FC8",
            "theme_color"          => "#FFFFFF",
            "related_applications" => [
                [
                    "platform" => "webapp",
                    "url"      => Url::root('/manifest.webmanifest')->render(),
                ]
            ]
        ];
    }

    /**
     * Page Hors ligne
     *
     * @return Response
     */
    public function offline()
    {
        return $this->view('app/offline', [
            'css' => $this->getOfflineCss()
        ]);
    }

    /**
     * Css Page Hors ligne
     *
     * @return string
     */
    protected function getOfflineCss(): string
    {
        ob_start();
        ?>
        body {
            background-color:#5A0FC8;
            color:#FFF;
            font-family:Arial, Helvetica Neue, Helvetica, sans-serif;
            text-align:center;
        }
        .PwaOffline {
            position: absolute;
            top:50%;
            left:0;
            right:0;
            transform:translateY(-50%)
        }
        .PwaOffline-title {
            font-weight:bolder;
            font-size:48px;
            text-transform:uppercase;
        }
        .PwaOffline-icon {
            width:128px;
            margin:0 auto;
        }
        .PwaOffline-icon > svg {
            fill:#FFF;
        }
        .PwaOffline-button {
            background-color: #FFF;
            color: #5A0FC8;
            padding:10px 30px;
            border-radius: 5px;
            border-style: solid;
            border-width:1px;
            border-color:#5A0FC8;
            font-weight:bold;
            font-size:14px;
            text-transform:uppercase;
            transition: color 300ms ease-out, background-color 300ms ease-out, border-color 300ms ease-out;
            cursor:pointer;
        }
        .PwaOffline-button:hover {
            border-color:#FFF;
            background-color:#5A0FC8;
            color:#FFF;
        }
        .PwaOffline-button:focus {
            outline:none;
        }
        <?php
        return ob_get_clean();
    }

    /**
     * Service Worker
     *
     * @return Response
     */
    public function serviceWorker(): Response
    {
        $content = file_get_contents($this->pwa()->resources()->path('assets/js/sw.js'));

        return $this->response($content, 200, ['Content-Type' => 'application/javascript']);
    }

    /**
     * Moteur d'affichage des gabarits d'affichage.
     *
     * @return Engine
     */
    public function viewEngine(): Engine
    {
        return View::getPlatesEngine([
            'directory' => $this->pwa()->resources('views')
        ]);
    }
}
