<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Adapter;

use Omnipay\Common\Message\RequestInterface;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * @phpcs:disable SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
 */
interface ShouldBeManagedInterface
{
    public const TAG = 'creatortsv_omnipay_manager.gateway';
    
    public static function getOmnipayGatewayAlias(): string;
}
