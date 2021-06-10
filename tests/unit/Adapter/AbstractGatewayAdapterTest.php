<?php

declare(strict_types=1);

namespace unit\Adapter;

use Creatortsv\OmnipayManagerBundle\Adapter\AbstractGatewayAdapter;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Http\ClientInterface;
use ReflectionException;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class AbstractGatewayAdapterTest extends KernelTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testSetGateway(): AbstractGatewayAdapter
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $adapter = $this->createMock(AbstractGatewayAdapter::class);
        $adapter->setGateway($gateway);

        $propertyGateway = new ReflectionProperty($adapter, 'gateway');
        $propertyGateway->setAccessible(true);
        $installed = $propertyGateway->getValue($adapter);

        $this->assertInstanceOf(GatewayInterface::class, $installed);
        $this->assertSame($gateway, $installed);

        return $adapter;
    }

    /**
     * @depends testSetGateway
     */
    public function testGetGateway(AbstractGatewayAdapter $adapter): AbstractGatewayAdapter
    {
        $this->assertInstanceOf(GatewayInterface::class, $adapter->getGateway());

        return $adapter;
    }

    /**
     * @depends testGetGateway
     */
    public function testProxyMethods(AbstractGatewayAdapter $adapter): void
    {
        $method = 'getName';
        $return = 'Test';
        $gateway = $this->createMock(GatewayInterface::class);
        $gateway->method($method)->willReturn($return);
        $adapter->setGateway($gateway);

        $this->assertSame($return, call_user_func([$adapter, $method]));
    }

    public function testCreateAdapter(): void
    {
        $gateway = $this->createMock(GatewayInterface::class);
        $adapter = new class (get_class($gateway)) extends AbstractGatewayAdapter
        {
            private static string $gatewayClass;

            public function __construct(string $gatewayClass)
            {
                static::$gatewayClass = $gatewayClass;
                parent::__construct();
            }

            public static function getOmnipayGatewayAlias(): string
            {
                return '\\' . static::$gatewayClass;
            }
        };

        $this->assertInstanceOf(GatewayInterface::class, $adapter->getGateway());
    }
}
