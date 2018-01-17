<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Tests;

use Mindy\Bundle\SocialAuthBundle\DependencyInjection\SocialAuthExtension;
use Mindy\Bundle\SocialAuthBundle\Provider\Facebook\Facebook;
use Mindy\Bundle\SocialAuthBundle\Provider\Google\Google;
use Mindy\Bundle\SocialAuthBundle\Provider\Odnoklassniki\Odnoklassniki;
use Mindy\Bundle\SocialAuthBundle\Provider\Twitter\Twitter;
use Mindy\Bundle\SocialAuthBundle\Provider\Vkontakte\Vkontakte;
use Mindy\Bundle\SocialAuthBundle\Provider\Yandex\Yandex;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtensionTest extends TestCase
{
    public function testEmptyConfigExtension()
    {
        $containerBuilder = new ContainerBuilder();

        $ext = new SocialAuthExtension();
        $ext->load([
            'social_auth' => []
        ], $containerBuilder);

        $this->assertFalse($containerBuilder->hasDefinition(Vkontakte::class));
        $this->assertFalse($containerBuilder->hasDefinition(Facebook::class));
        $this->assertFalse($containerBuilder->hasDefinition(Odnoklassniki::class));
        $this->assertFalse($containerBuilder->hasDefinition(Twitter::class));
        $this->assertFalse($containerBuilder->hasDefinition(Google::class));
        $this->assertFalse($containerBuilder->hasDefinition(Yandex::class));
    }

    public function testExtension()
    {
        $containerBuilder = new ContainerBuilder();

        $ext = new SocialAuthExtension();
        $expected = ['client_id' => 123, 'client_secret' => 123];
        $ext->load([
            'social_auth' => [
                'vkontakte' => $expected,
                'facebook' => $expected,
                'google' => $expected,
                'yandex' => $expected,
                'odnoklassniki' => $expected,
                'twitter' => $expected,
            ]
        ], $containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition(Vkontakte::class));
        $this->assertTrue($containerBuilder->hasDefinition(Facebook::class));
        $this->assertTrue($containerBuilder->hasDefinition(Odnoklassniki::class));
        $this->assertTrue($containerBuilder->hasDefinition(Twitter::class));
        $this->assertTrue($containerBuilder->hasDefinition(Google::class));
        $this->assertTrue($containerBuilder->hasDefinition(Yandex::class));

        $this->assertArguments($containerBuilder, Vkontakte::class, [
            'clientId' => 123,
            'clientSecret' => 123,
            'scope' => ['email', 'offline']
        ]);
        $this->assertArguments($containerBuilder, Facebook::class, [
            'clientId' => 123,
            'clientSecret' => 123,
            'scope' => ['email', 'public_profile'],
            'graphApiVersion' => 'v2.10'
        ]);
        $this->assertArguments($containerBuilder, Odnoklassniki::class, [
            'clientId' => 123,
            'clientSecret' => 123,
        ]);
        $this->assertArguments($containerBuilder, Twitter::class, [
            'identifier' => 123,
            'secret' => 123,
        ]);
        $this->assertArguments($containerBuilder, Google::class, [
            'clientId' => 123,
            'clientSecret' => 123,
        ]);
        $this->assertArguments($containerBuilder, Yandex::class, [
            'clientId' => 123,
            'clientSecret' => 123,
        ]);
    }

    protected function assertArguments(ContainerBuilder $container, $definition, array $expected)
    {
        $this->assertEquals(
            $expected,
            $container->getDefinition($definition)->getArgument(0)
        );
    }
}
