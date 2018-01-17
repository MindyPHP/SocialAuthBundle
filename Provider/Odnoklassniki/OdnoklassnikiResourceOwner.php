<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Provider\Odnoklassniki;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Mindy\Bundle\SocialAuthBundle\Provider\SocialUserInterface;

class OdnoklassnikiResourceOwner implements ResourceOwnerInterface, SocialUserInterface
{
    /**
     * @var array
     */
    private $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->response['uid'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->response['name'];
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->response['first_name'];
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->response['last_name'];
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->response['pic_3'];
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->response['gender'];
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->response['location']['city'];
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->response['locale'];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->response;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        if (isset($this->response['email'])) {
            return $this->response['email'];
        }

        return null;
    }
}
