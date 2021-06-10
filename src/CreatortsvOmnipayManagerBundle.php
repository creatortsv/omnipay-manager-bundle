<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle;

use Creatortsv\OmnipayManagerBundle\DependencyInjection\Compiler\CreatortsvOmnipayManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CreatortsvOmnipayManagerBundle
 *
 * @phpcs:disable SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
 */
class CreatortsvOmnipayManagerBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CreatortsvOmnipayManagerPass());
    }
}
