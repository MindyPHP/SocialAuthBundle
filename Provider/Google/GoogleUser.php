<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Provider\Google;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Mindy\Bundle\SocialAuthBundle\Provider\SocialUserInterface;

class GoogleUser implements ResourceOwnerInterface, SocialUserInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId(): string
    {
        return $this->response['id'];
    }

    /**
     * Get preferred display name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->response['displayName'];
    }

    /**
     * Get preferred first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->response['name']['givenName'];
    }

    /**
     * Get preferred last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->response['name']['familyName'];
    }

    /**
     * Get email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        if (!empty($this->response['emails'])) {
            return $this->response['emails'][0]['value'];
        }
    }

    /**
     * Get avatar image URL.
     *
     * @return string|null
     */
    public function getAvatar()
    {
        if (!empty($this->response['image']['url'])) {
            return $this->response['image']['url'];
        }
    }

    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
