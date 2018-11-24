<?php

namespace tiFy\Plugins\Pwa\Push;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use tiFy\Plugins\Pwa\Contracts\PwaManager;

class PwaPushSend
{
    /**
     * Options d'authentification.
     * @var array
     */
    protected $vapid = [
        'subject'    => 'https://tigreblanc.fr',
        'publicKey'  => 'BA3AaUmVuw2tXjIMs7z9Q53/lB4PLIwlUxOIiijBqSMfTGd+LGnpR/OYCd4MvYkAILRUiKmt4fPuXXj9lvVhQ7Q=',
        'privateKey' => 'MD70X0FTTN+Kn4wjOFB3hy46EslWK/3zUGEW5gMBroM=',
    ];

    /**
     * Instanciation de la classe.
     *
     * @return void
     */
    public function __invoke()
    {
        /** @var \tiFy\Plugins\Pwa\Push\PwaPushSubscriber $subscriber */
        $subscriber = $this->app->get('pwa.push.subscriber');

        $notifications = [];
        $payload = 'Ceci est un test';

        foreach ($subscriber->all() as $row) :
            try {
                $subscription = new Subscription(
                    $row['endpoint'],
                    $row['publickey'],
                    $row['authtoken'],
                    "aesgcm"
                );
            } catch (\ErrorException $e) {
                continue;
            }
            $notifications[] = compact('subscription', 'payload');
        endforeach;

        $auth = [
            'VAPID' => $this->vapid
        ];

        try {
            $webPush = new WebPush($auth);
        } catch (\ErrorException $e) {
            exit($e->getMessage());
        }

        foreach ($notifications as $notification) :
            try {
                $webPush->sendNotification($notification['subscription'], $notification['payload'], false);
            } catch (\ErrorException $e) {
                continue;
            }
        endforeach;

        try {
            $webPush->flush();
        } catch (\ErrorException $e) {
            exit($e->getMessage());
        }
    }
}