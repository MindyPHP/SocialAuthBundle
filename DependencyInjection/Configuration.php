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
            ->end();

        $this->addGoogleSection($rootNode);
        $this->addYandexSection($rootNode);
        $this->addTwitterSection($rootNode);
        $this->addFacebookSection($rootNode);
        $this->addOdnoklassnikiSection($rootNode);

        return $treeBuilder;
    }

    private function addOdnoklassnikiSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('odnoklassniki')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('client_id')->defaultNull()->end()
                        ->scalarNode('client_secret')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addTwitterSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('twitter')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('client_id')->defaultNull()->end()
                        ->scalarNode('client_secret')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addYandexSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('yandex')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('client_id')->defaultNull()->end()
                        ->scalarNode('client_secret')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addGoogleSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('google')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('client_id')->defaultNull()->end()
                        ->scalarNode('client_secret')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();
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

    private function addFacebookSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('facebook')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('client_id')->defaultNull()->end()
                        ->scalarNode('client_secret')->defaultNull()->end()
                        ->scalarNode('graph_api_version')->defaultValue('v2.10')->end()
                        ->arrayNode('scope')
                            ->scalarPrototype()->end()
                            ->defaultValue(['email', 'public_profile'])
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
