<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle;

use Creatortsv\OmnipayManagerBundle\Adapter\OmnipayGatewayAdapter;
use Creatortsv\OmnipayManagerBundle\Exception\GatewayIsNotRegisteredException;
use Exception;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Helper;

final class GatewayManager
{
    /**
     * @param array<string, OmnipayGatewayAdapter> $adapters
     */
    public function __construct(private array $adapters = [])
    {
        // ...
    }

    public function addAdapter(OmnipayGatewayAdapter $adapter): void
    {
        $this->adapters[$adapter::getOmnipayGatewayAlias()] = $adapter;
    }

    /**
     * @template T of GatewayInterface
     * @param class-string<T>|string $alias {@see Helper::getGatewayClassName}
     * @return OmnipayGatewayAdapter<T>|OmnipayGatewayAdapter
     * @throws Exception
     */
    public function get(string $alias): OmnipayGatewayAdapter
    {
        return $this->adapters[$alias] ?? throw new GatewayIsNotRegisteredException($alias);
    }
}
