<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Adapter;

interface ShouldBeManagedInterface
{
    public const TAG = 'creatortsv_omnipay_manager.gateway';
    
    public static function getOmnipayGatewayAlias(): string;
}
