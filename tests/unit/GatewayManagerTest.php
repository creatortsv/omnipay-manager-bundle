<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Tests\Unit;

use Creatortsv\OmnipayManagerBundle\Adapter\AbstractGatewayAdapter;
use Creatortsv\OmnipayManagerBundle\GatewayManager;
use Exception;
use InvalidArgumentException;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GatewayManagerTest extends KernelTestCase
{
    protected ContainerInterface $serviceContainer;

    public function setUp(): void
    {
        parent::setUp();
        static::bootKernel();

        $this->serviceContainer = static::$kernel->getContainer();
    }

    /**
     * @return array<GatewayManager>
     * @throws Exception
     */
    public function testGetGatewayManager(): array
    {
        $injected = $this
            ->serviceContainer
            ->get(GatewayManager::class);

        $directly = GatewayManager::instance($this->serviceContainer);

        $this->assertInstanceOf(GatewayManager::class, $injected);
        $this->assertInstanceOf(GatewayManager::class, $directly);
        $this->assertEquals($directly, $injected);

        return [$injected, $directly];
    }

    /**
     * @depends testGetGatewayManager
     *
     * @param array<GatewayManager> $managerInstances
     * @return array<GatewayManager>
     */
    public function testAddGatewayAdapter(array $managerInstances): array
    {
        [$injected, $directly] = $managerInstances;

        $gateway = 'Some';
        $adapter = $this->createAdapter($gateway);
        $adapterClass = get_class($adapter);

        $injected->addGatewayAdapter($adapterClass);

        $adaptersProperty = new ReflectionProperty(GatewayManager::class, 'adapters');
        $expectedAdapters = [$gateway => $adapterClass];

        $this->assertSame($expectedAdapters, $adaptersProperty->getValue($injected));
        $this->assertSame($expectedAdapters, $adaptersProperty->getValue($directly));

        return [$injected, $adapter];
    }

    /**
     * @depends testAddGatewayAdapter
     *
     * @param array<GatewayManager|AbstractGatewayAdapter> $arguments
     * @throws Exception
     */
    public function testUseGatewayAdapter(array $arguments): void
    {
        [$injected, $adapter] = $arguments;

        $adapterClass = get_class($adapter);
        $gatewayAlias = $adapterClass::getOmnipayGatewayAlias();
        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock
            ->method('get')
            ->with($adapterClass)
            ->willReturn($adapter);

        $directly = GatewayManager::instance($containerMock);

        $this->assertInstanceOf($adapterClass, $injected->use($gatewayAlias));
        $this->assertInstanceOf($adapterClass, $directly->use($gatewayAlias));
    }

    public function testErrorWhenAddAdapter(): void
    {
        $manager = GatewayManager::instance($this->serviceContainer);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Adapter class "UndefinedAdapterClass" not found');

        $manager->addGatewayAdapter('UndefinedAdapterClass');
    }

    /**
     * @throws Exception
     */
    public function testErrorWhenUseAdapter(): void
    {
        $manager = GatewayManager::instance($this->serviceContainer);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Gateway adapter for the alias "UndefinedAlias" is not registered');

        $manager->use('UndefinedAlias');
    }

    private function createAdapter(string $gatewayAlias): AbstractGatewayAdapter
    {
        return new class ($gatewayAlias) extends AbstractGatewayAdapter
        {
            private static string $alias;

            public function __construct(string $alias)
            {
                static::$alias = $alias;
            }

            public static function getOmnipayGatewayAlias(): string
            {
                return static::$alias;
            }
        };
    }
}
