<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\DependencyInjection;

use Creatortsv\OmnipayManagerBundle\Adapter\ShouldBeManagedInterface;
use Exception;
use phpDocumentor\Reflection\Types\Mixed_;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CreatortsvOmnipayManagerExtension extends Extension
{
    /**
     * @param array<Mixed_> $configs
     * @throws Exception
     *
     * @phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yml');

        $container
            ->registerForAutoconfiguration(ShouldBeManagedInterface::class)
            ->addTag(ShouldBeManagedInterface::TAG);
    }
}
