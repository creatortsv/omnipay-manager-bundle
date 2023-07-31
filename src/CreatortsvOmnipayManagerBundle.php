<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle;

use Creatortsv\OmnipayManagerBundle\DependencyInjection\CompilerPass\OmnipayAdapterCompilerPass;
use Creatortsv\OmnipayManagerBundle\DependencyInjection\CompilerPass\OmnipayManagerCompilerPass;
use Creatortsv\OmnipayManagerBundle\DependencyInjection\CreatortsvOmnipayManagerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Class CreatortsvOmnipayManagerBundle
 */
class CreatortsvOmnipayManagerBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new OmnipayAdapterCompilerPass());
        $container->addCompilerPass(new OmnipayManagerCompilerPass());
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new CreatortsvOmnipayManagerExtension();
    }
}
