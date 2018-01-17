<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialBundle\Registry;

use League\OAuth1\Client\Server\Server;
use League\OAuth2\Client\Provider\AbstractProvider;

class SocialRegistry
{
    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @param $id
     * @param $provider
     */
    public function addProvider($id, $provider)
    {
        if ($this->hasProvider($id)) {
            throw new \RuntimeException(sprintf(
                'Provider with id %s already exists',
                $id
            ));
        }

        $this->providers[$id] = $provider;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasProvider($id): bool
    {
        return array_key_exists($id, $this->providers);
    }

    /**
     * @param $id
     *
     * @return AbstractProvider|Server
     */
    public function getProvider($id)
    {
        return $this->providers[$id];
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->providers;
    }
}
