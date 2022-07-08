<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\DependencyInjection\Compiler;

use Creatortsv\OmnipayManagerBundle\Adapter\ShouldBeManagedInterface;
use Creatortsv\OmnipayManagerBundle\GatewayManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CreatortsvOmnipayManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(GatewayManager::class)) {
            return;
        }

        $definition = $container->findDefinition(GatewayManager::class);
        $adapterIds = array_keys($container->findTaggedServiceIds(ShouldBeManagedInterface::TAG));

        foreach ($adapterIds as $id) {
            $adapterDefinition = $container->getDefinition($id);
            $adapterDefinition->setPublic(true);
            $definition->addMethodCall('addGatewayAdapter', [$adapterDefinition->getClass()]);
        }
    }
}
