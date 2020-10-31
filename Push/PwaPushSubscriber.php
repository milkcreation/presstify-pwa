<?php

namespace tiFy\Plugins\Pwa\Push;

class PwaPushSubscriber extends \PDO
{
    /**
     * Récupération de la liste complètes des abonnés.
     *
     * @return \PDOStatement|bool
     */
    public function all()
    {
        return $this->query("SELECT * FROM subscribe WHERE 1", self::FETCH_ASSOC);
    }

    /**
     * Ajout d'un abonné.
     *
     * @param string $endpoint
     * @param string $publickey
     * @param string $authtoken
     *
     * @return int
     */
    public function add($endpoint, $publickey, $authtoken)
    {
        $this->query(
            "INSERT INTO subscribe (endpoint, publickey, auth) VALUES ('{$endpoint}', '{$publickey}', '{$authtoken}')"
        );

        return self::lastInsertId();
    }
}