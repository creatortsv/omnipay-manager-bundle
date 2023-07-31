<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Adapter;

use Creatortsv\OmnipayManagerBundle\Factory\OmnipayGatewayFactory;
use Omnipay\Common\GatewayInterface;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @template TGateway of GatewayInterface
 */
abstract class OmnipayGatewayAdapter implements GatewayAdapterInterface
{
    protected GatewayInterface $gateway;

    protected bool $isMustBeInitialized = true;

    abstract public static function getOmnipayGatewayAlias(): string;

    /**
     * @internal
     */
    public function setGateway(OmnipayGatewayFactory $gatewayFactory): void
    {
        $this->gateway = $gatewayFactory->create(
            self::getOmnipayGatewayAlias(),
            $this->getHttpClient(),
            $this->getHttpRequest(),
        );
    }

    /**
     * @return TGateway
     */
    public function getOmnipayGateway(): GatewayInterface
    {
        if ($this->isMustBeInitialized) {
            $this->isMustBeInitialized = false;
            $this->init();
        }

        return $this->gateway;
    }

    protected function getHttpClient(): ?ClientInterface
    {
        return null;
    }

    protected function getHttpRequest(): ?Request
    {
        return null;
    }

    protected function init(): void
    {
        // ...
    }

    public function __call(string $method, array $arguments): mixed
    {
        return $this->getOmnipayGateway()->$method(...$arguments);
    }
}
