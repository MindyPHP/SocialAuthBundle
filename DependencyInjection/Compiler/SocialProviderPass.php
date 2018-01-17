<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialBundle\DependencyInjection\Compiler;

use Mindy\Bundle\SocialBundle\Registry\SocialRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SocialProviderPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(SocialRegistry::class)) {
            return;
        }

        $registryDefinition = $container->getDefinition(SocialRegistry::class);

        foreach ($container->findTaggedServiceIds('social.provider') as $id => $config) {
            $params = array_shift($config);

            $providerDefinition = $container->getDefinition($id);
            $providerDefinition->setPublic(true);

            if (!isset($params['provider'])) {
                throw new \LogicException('Incorrect social provider configuration. Please set "provider" name.');
            }

            $registryDefinition->addMethodCall('addProvider', [
                $params['provider'],
                new Reference($id),
            ]);
        }
    }
}
