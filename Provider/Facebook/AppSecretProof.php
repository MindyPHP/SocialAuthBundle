<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialBundle\Provider\Facebook;

class AppSecretProof
{
    /**
     * The app secret proof to sign requests made to the Graph API
     *
     * @see https://developers.facebook.com/docs/graph-api/securing-requests#appsecret_proof
     *
     * @param string $appSecret
     * @param string $accessToken
     *
     * @return string
     */
    public static function create($appSecret, $accessToken)
    {
        return hash_hmac('sha256', $accessToken, $appSecret);
    }
}
