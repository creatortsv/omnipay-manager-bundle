<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Tests;

use Creatortsv\OmnipayManagerBundle\CreatortsvOmnipayManagerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * @return array<BundleInterface>
     */
    public function registerBundles(): array
    {
        return [
            new CreatortsvOmnipayManagerBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // TODO: Implement registerContainerConfiguration() method.
    }
}
