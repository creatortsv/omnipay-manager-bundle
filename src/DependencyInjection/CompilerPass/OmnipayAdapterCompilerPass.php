<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\DependencyInjection\CompilerPass;

use Creatortsv\OmnipayManagerBundle\Adapter\GatewayAdapterInterface;
use Creatortsv\OmnipayManagerBundle\Factory\OmnipayGatewayFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OmnipayAdapterCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        foreach (array_keys($container->findTaggedServiceIds(GatewayAdapterInterface::class)) as $serviceID) {
            $container
                ->findDefinition($serviceID)
                ->addMethodCall('setGateway', [new Reference(OmnipayGatewayFactory::class)]);
        }
    }
}
