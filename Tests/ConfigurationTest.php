<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Tests;

use Mindy\Bundle\SocialAuthBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function configurationProvider()
    {
        return [
            // Vkontakte
            [
                'vkontakte',
                [
                    'client_id' => 123,
                    'client_secret' => 123,
                    'scope' => ['email'],
                ],
                [
                    'enabled' => true,
                    'client_id' => 123,
                    'client_secret' => 123,
                    'scope' => ['email'],
                ],
            ],
            // Facebook
            [
                'facebook',
                [
                    'client_id' => 123,
                    'client_secret' => 123,
                    'scope' => ['email'],
                ],
                [
                    'enabled' => true,
                    'client_id' => 123,
                    'client_secret' => 123,
                    'scope' => ['email'],
                    'graph_api_version' => 'v2.10',
                ],
            ],
            // Twitter
            [
                'twitter',
                [
                    'client_id' => 123,
                    'client_secret' => 123,
                ],
                [
                    'enabled' => true,
                    'client_id' => 123,
                    'client_secret' => 123,
                ],
            ],
            // Google
            [
                'google',
                [
                    'client_id' => 123,
                    'client_secret' => 123,
                ],
                [
                    'enabled' => true,
                    'client_id' => 123,
                    'client_secret' => 123,
                ],
            ],
            // Yandex
            [
                'yandex',
                [
                    'client_id' => 123,
                    'client_secret' => 123,
                ],
                [
                    'enabled' => true,
                    'client_id' => 123,
                    'client_secret' => 123,
                ],
            ],
        ];
    }

    /**
     * @dataProvider configurationProvider
     *
     * @param array  $config
     * @param string $key
     * @param array  $expected
     */
    public function testConfiguration(string $key, array $config, array $expected)
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $result = $processor->processConfiguration($configuration, ['social_auth' => [$key => $config]]);
        $this->assertEquals(
            $expected,
            $result[$key]
        );
    }

    public function testEmptyConfiguration()
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $result = $processor->processConfiguration($configuration, [
            'social_auth' => [],
        ]);

        $this->assertEquals(
            [
                    'vkontakte' => [
                        'enabled' => false,
                        'client_id' => null,
                        'client_secret' => null,
                        'scope' => ['email', 'offline'],
                    ],
                    'google' => [
                        'enabled' => false,
                        'client_id' => null,
                        'client_secret' => null,
                    ],
                    'yandex' => [
                        'enabled' => false,
                        'client_id' => null,
                        'client_secret' => null,
                    ],
                    'twitter' => [
                        'enabled' => false,
                        'client_id' => null,
                        'client_secret' => null,
                    ],
                    'facebook' => [
                        'enabled' => false,
                        'client_id' => null,
                        'client_secret' => null,
                        'graph_api_version' => 'v2.10',
                        'scope' => ['email', 'public_profile'],
                    ],
                    'odnoklassniki' => [
                        'enabled' => false,
                        'client_id' => null,
                        'client_secret' => null,
                    ],
            ],
            $result
        );
    }
}
