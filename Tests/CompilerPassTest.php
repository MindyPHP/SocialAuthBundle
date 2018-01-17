<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 17/01/2018
 * Time: 20:43
 */

namespace Mindy\Bundle\SocialAuthBundle\Tests;

use Mindy\Bundle\SocialAuthBundle\DependencyInjection\Compiler\SocialProviderPass;
use Mindy\Bundle\SocialAuthBundle\Provider\Twitter\Twitter;
use Mindy\Bundle\SocialAuthBundle\Registry\SocialRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class CompilerPassTest extends TestCase
{
    public function testCompiler()
    {
        $container = new ContainerBuilder();
        $c = new SocialProviderPass();
        $this->assertNull($c->process($container));

        $container = new ContainerBuilder();
        $container->setDefinition(SocialRegistry::class, new Definition(SocialRegistry::class));

        $provider = new Definition(Twitter::class);
        $provider->setPrivate(true);
        $provider->setTags([
            'social.provider' => [
                ['provider' => 'twitter']
            ]
        ]);

        $container->setDefinition(Twitter::class, $provider);

        $c = new SocialProviderPass();
        $c->process($container);

        $valid = $container->getDefinition(Twitter::class);
        $this->assertTrue($valid->isPublic());

        $this->assertCount(1, $container->findTaggedServiceIds('social.provider'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testCompilerException()
    {
        $provider = new Definition(Twitter::class);
        $provider->setTags([
            'social.provider' => [
                ['foo' => 'bar']
            ]
        ]);

        $container = new ContainerBuilder();
        $container->setDefinition(SocialRegistry::class, new Definition(SocialRegistry::class));
        $container->setDefinition(Twitter::class, $provider);

        $compiler = new SocialProviderPass();
        $compiler->process($container);
    }
}
