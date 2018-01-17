<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Provider\Facebook;

use League\OAuth2\Client\Grant\AbstractGrant;

class FbExchangeToken extends AbstractGrant
{
    public function __toString()
    {
        return 'fb_exchange_token';
    }

    protected function getRequiredRequestParameters()
    {
        return [
            'fb_exchange_token',
        ];
    }

    protected function getName()
    {
        return 'fb_exchange_token';
    }
}
