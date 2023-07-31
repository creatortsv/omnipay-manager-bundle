<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\DependencyInjection;

use Creatortsv\OmnipayManagerBundle\Adapter\GatewayAdapterInterface;
use Creatortsv\OmnipayManagerBundle\Factory\OmnipayGatewayFactory;
use Creatortsv\OmnipayManagerBundle\GatewayManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

class CreatortsvOmnipayManagerExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(OmnipayGatewayFactory::class, OmnipayGatewayFactory::class);
        $container->registerForAutoconfiguration(GatewayAdapterInterface::class)->addTag(
            GatewayAdapterInterface::class,
        );

        $definition = new Definition(GatewayManager::class);
        $definition->setShared(true);
        $definition->setPublic($container->getParameter('kernel.environment') === 'test');

        $container->setDefinition(GatewayManager::class, $definition);
    }
}
