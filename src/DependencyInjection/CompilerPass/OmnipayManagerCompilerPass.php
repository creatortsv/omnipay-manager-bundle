<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\DependencyInjection\CompilerPass;

use Creatortsv\OmnipayManagerBundle\Adapter\GatewayAdapterInterface;
use Creatortsv\OmnipayManagerBundle\GatewayManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OmnipayManagerCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(GatewayManager::class);
        $adapterIDs = array_keys($container->findTaggedServiceIds(GatewayAdapterInterface::class));

        foreach ($adapterIDs as $serviceId) {
            $definition->addMethodCall('addAdapter', [new Reference($serviceId)]);
        }
    }
}
