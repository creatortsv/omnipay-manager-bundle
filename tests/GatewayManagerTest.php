<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Tests;

use Creatortsv\OmnipayManagerBundle\Adapter\OmnipayGatewayAdapter;
use Creatortsv\OmnipayManagerBundle\GatewayManager;
use Exception;
use PHPUnit\Framework\TestCase;

class GatewayManagerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGet(): void
    {
        $adapter = $this->createMock(OmnipayGatewayAdapter::class);
        $manager = new GatewayManager(['Mock' => $adapter]);

        $this->assertSame($adapter, $manager->get('Mock'));
    }

    /**
     * @throws Exception
     */
    public function testGetIsNotRegistered(): void
    {
        $this->expectExceptionMessage('Gateway adapter for the alias "Mock" is not registered');

        $manager = new GatewayManager();
        $manager->get('Mock');
    }
}
