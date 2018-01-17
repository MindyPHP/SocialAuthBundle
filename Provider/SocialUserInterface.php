<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialBundle\Provider;

interface SocialUserInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string|null
     */
    public function getEmail();
}
