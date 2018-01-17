<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialBundle\DependencyInjection;

use Mindy\Bundle\SocialBundle\Provider\Facebook\Facebook;
use Mindy\Bundle\SocialBundle\Provider\Google\Google;
use Mindy\Bundle\SocialBundle\Provider\Odnoklassniki\Odnoklassniki;
use Mindy\Bundle\SocialBundle\Provider\Twitter\Twitter;
use Mindy\Bundle\SocialBundle\Provider\Vkontakte\Vkontakte;
use Mindy\Bundle\SocialBundle\Provider\Yandex\Yandex;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SocialExtension extends Extension
{
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
            $this->registerVkontakteConfiguration($config, $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['facebook'])) {
            $this->registerFacebookConfiguration($config, $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['twitter'])) {
            $this->registerTwitterConfiguration($config, $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['google'])) {
            $this->registerGoogleConfiguration($config, $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['yandex'])) {
            $this->registerYandexConfiguration($config, $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['odnoklassniki'])) {
            $this->registerOdnoklassnikiConfiguration($config, $container, $loader);
        }
    }

    private function registerVkontakteConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('provider/vkontakte.yaml');

        $container
            ->getDefinition(Vkontakte::class)
            ->setArguments(array_values($config));
    }

    private function registerFacebookConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('provider/facebook.yaml');

        $container
            ->getDefinition(Facebook::class)
            ->setArguments(array_values($config));
    }

    private function registerTwitterConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('provider/twitter.yaml');

        $container
            ->getDefinition(Twitter::class)
            ->setArguments(array_values($config));
    }

    private function registerGoogleConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('provider/google.yaml');

        $container
            ->getDefinition(Google::class)
            ->setArguments(array_values($config));
    }

    private function registerYandexConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('provider/yandex.yaml');

        $container
            ->getDefinition(Yandex::class)
            ->setArguments(array_values($config));
    }

    private function registerOdnoklassnikiConfiguration(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('provider/odnoklassniki.yaml');

        $container
            ->getDefinition(Odnoklassniki::class)
            ->setArguments(array_values($config));
    }
}
