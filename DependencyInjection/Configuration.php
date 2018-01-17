<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('social_auth');
        $rootNode
            ->children()
            ->append($this->providerVkontakte())
            ->append($this->providerGoogle())
            ->append($this->providerFacebook())
            ->append($this->providerYandex())
            ->append($this->providerTwitter())
            ->append($this->providerOdnoklassniki())
            ->end();

        return $treeBuilder;
    }

    private function providerOdnoklassniki()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('odnoklassniki');

        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('client_id')->defaultNull()->end()
                ->scalarNode('client_secret')->defaultNull()->end()
            ->end();

        return $node;
    }

    private function providerTwitter()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('twitter');

        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('client_id')->defaultNull()->end()
                ->scalarNode('client_secret')->defaultNull()->end()
            ->end();

        return $node;
    }

    private function providerYandex()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('yandex');

        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('client_id')->defaultNull()->end()
                ->scalarNode('client_secret')->defaultNull()->end()
            ->end();

        return $node;
    }

    private function providerGoogle()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('google');

        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('client_id')->defaultNull()->end()
                ->scalarNode('client_secret')->defaultNull()->end()
            ->end();

        return $node;
    }

    private function providerVkontakte()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('vkontakte');

        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('client_id')->defaultNull()->end()
                ->scalarNode('client_secret')->defaultNull()->end()
                ->arrayNode('scope')
                    ->scalarPrototype()->end()
                    ->defaultValue(['email', 'offline'])
                ->end()
            ->end();

        return $node;
    }

    private function providerFacebook()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('facebook');

        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('client_id')->defaultNull()->end()
                ->scalarNode('client_secret')->defaultNull()->end()
                ->scalarNode('graph_api_version')->defaultValue('v2.10')->end()
                ->arrayNode('scope')
                    ->scalarPrototype()->end()
                    ->defaultValue(['email', 'public_profile'])
                ->end()
            ->end();

        return $node;
    }
}
