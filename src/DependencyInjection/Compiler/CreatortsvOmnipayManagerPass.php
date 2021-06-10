<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\DependencyInjection\Compiler;

use Creatortsv\OmnipayManagerBundle\Adapter\ShouldBeManagedInterface;
use Creatortsv\OmnipayManagerBundle\GatewayManger;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @phpcs:disable SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
 */
class CreatortsvOmnipayManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(GatewayManger::class)) {
            return;
        }

        $definition = $container->findDefinition(GatewayManger::class);
        $adapterIds = array_keys($container->findTaggedServiceIds(ShouldBeManagedInterface::TAG));

        foreach ($adapterIds as $id) {
            $adapterDefinition = $container->getDefinition($id);
            $adapterDefinition->setPublic(true);
            $definition->addMethodCall('addGatewayAdapter', [$adapterDefinition->getClass()]);
        }
    }
}
