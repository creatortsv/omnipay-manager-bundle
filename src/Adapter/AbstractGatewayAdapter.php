<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Adapter;

use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Omnipay;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractGatewayAdapter implements ShouldBeManagedInterface
{
    protected GatewayInterface $gateway;
    
    public function __construct()
    {
        $this->gateway = Omnipay::create(
            static::getOmnipayGatewayAlias(),
            $this->getHttpClient(),
            $this->getHttpRequest(),
        );
    }

    final public function getGateway(): GatewayInterface
    {
        return $this->gateway;
    }
    
    final public function setGateway(GatewayInterface $gateway): AbstractGatewayAdapter
    {
        $this->gateway = $gateway;
        
        return $this;
    }

    protected function getHttpClient(): ?ClientInterface
    {
        return null;
    }

    protected function getHttpRequest(): ?Request
    {
        return null;
    }

    final public function __call(string $method, array $arguments): mixed
    {
        return $this->getGateway()->$method(...$arguments);
    }
}
