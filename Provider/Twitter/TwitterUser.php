<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialBundle\Provider\Twitter;

use League\OAuth1\Client\Server\User;
use Mindy\Bundle\SocialBundle\Provider\SocialUserInterface;
use ReflectionProperty;

class TwitterUser extends User implements SocialUserInterface
{
    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];

        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $array[$property->getName()] = $this->{$property->getName()};
        }

        return $array;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->uid;
    }
}
