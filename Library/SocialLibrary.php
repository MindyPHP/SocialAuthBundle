<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Library;

use Mindy\Bundle\SocialAuthBundle\Registry\SocialRegistry;
use Mindy\Template\Library;

class SocialLibrary extends Library
{
    /**
     * @var SocialRegistry
     */
    protected $registry;

    /**
     * SocialLibrary constructor.
     *
     * @param SocialRegistry $registry
     */
    public function __construct(SocialRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'get_social_providers' => function () {
                return array_keys($this->registry->all());
            },
        ];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return [];
    }
}
