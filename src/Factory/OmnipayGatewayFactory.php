<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Factory;

use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Http\Client;
use Omnipay\Omnipay;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @template TGateway of GatewayInterface
 */
class OmnipayGatewayFactory
{
    /**
     * @return TGateway
     */
    public function create(string $gateway, ?ClientInterface $client = null, ?Request $request = null): GatewayInterface
    {
        if ($client !== null) {
            $client = new Client($client);
        }

        return Omnipay::create($gateway, $client, $request);
    }
}
