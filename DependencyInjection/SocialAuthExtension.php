<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\DependencyInjection;

use Mindy\Bundle\SocialAuthBundle\Provider\Facebook\Facebook;
use Mindy\Bundle\SocialAuthBundle\Provider\Google\Google;
use Mindy\Bundle\SocialAuthBundle\Provider\Odnoklassniki\Odnoklassniki;
use Mindy\Bundle\SocialAuthBundle\Provider\Twitter\Twitter;
use Mindy\Bundle\SocialAuthBundle\Provider\Vkontakte\Vkontakte;
use Mindy\Bundle\SocialAuthBundle\Provider\Yandex\Yandex;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SocialAuthExtension extends Extension
{
    const TAG_NAME = 'social.provider';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if ($this->isConfigEnabled($container, $config['vkontakte'])) {
            $this->registerVkontakteConfiguration($config['vkontakte'], $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['facebook'])) {
            $this->registerFacebookConfiguration($config['facebook'], $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['twitter'])) {
            $this->registerTwitterConfiguration($config['twitter'], $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['google'])) {
            $this->registerGoogleConfiguration($config['google'], $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['yandex'])) {
            $this->registerYandexConfiguration($config['yandex'], $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['odnoklassniki'])) {
            $this->registerOdnoklassnikiConfiguration($config['odnoklassniki'], $container, $loader);
        }
    }

    private function registerVkontakteConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setDefinition(
            Vkontakte::class,
            $this->createProviderDefinition(Vkontakte::class, [
                [
                    'clientId' => $config['client_id'],
                    'clientSecret' => $config['client_secret'],
                    'scope' => $config['scope'],
                ]
            ], 'vkontakte')
        );
    }

    private function registerFacebookConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setDefinition(
            Facebook::class,
            $this->createProviderDefinition(Facebook::class, [
                [
                    'clientId' => $config['client_id'],
                    'clientSecret' => $config['client_secret'],
                    'scope' => $config['scope'],
                    'graphApiVersion' => $config['graph_api_version'],
                ]
            ], 'facebook')
        );
    }

    private function createProviderDefinition(string $class, array $arguments, string $provider)
    {
        $definition = new Definition($class, $arguments);
        $definition->setPublic(true);
        $definition->setTags([
            self::TAG_NAME => [
                ['provider' => $provider]
            ]
        ]);

        return $definition;
    }

    private function registerTwitterConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setDefinition(
            Twitter::class,
            $this->createProviderDefinition(Twitter::class, [
                [
                    'identifier' => $config['client_id'],
                    'secret' => $config['client_secret'],
                ]
            ], 'twitter')
        );
    }

    private function registerGoogleConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setDefinition(
            Google::class,
            $this->createProviderDefinition(Google::class, [
                [
                    'clientId' => $config['client_id'],
                    'clientSecret' => $config['client_secret'],
                ]
            ], 'google')
        );
    }

    private function registerYandexConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setDefinition(
            Yandex::class,
            $this->createProviderDefinition(Yandex::class, [
                [
                    'clientId' => $config['client_id'],
                    'clientSecret' => $config['client_secret'],
                ]
            ], 'yandex')
        );
    }

    private function registerOdnoklassnikiConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setDefinition(
            Odnoklassniki::class,
            $this->createProviderDefinition(Odnoklassniki::class, [
                [
                    'clientId' => $config['client_id'],
                    'clientSecret' => $config['client_secret'],
                ]
            ], 'odnoklassniki')
        );
    }
}
