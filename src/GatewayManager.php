<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle;

use Creatortsv\OmnipayManagerBundle\Adapter\AbstractGatewayAdapter;
use Exception;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @phpcs:disable SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
 */
final class GatewayManager
{
    /**
     * @var array<string|AbstractGatewayAdapter>
     */
    private array $adapters;
    
    private ContainerInterface $container;
    
    private static ?GatewayManager $instance = null;

    private function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }
    
    public static function instance(ContainerInterface $container): GatewayManager
    {
        if (self::$instance === null) {
            self::$instance = new self($container);
        }

        self::$instance->setContainer($container);

        return self::$instance;
    }
    
    public function addGatewayAdapter(string $adapterClass): GatewayManager
    {
        if (!class_exists($adapterClass)) {
            throw new InvalidArgumentException(
                sprintf('Adapter class "%s" not found', $adapterClass),
            );
        }
        
        $this->adapters[call_user_func([$adapterClass, 'getOmnipayGatewayAlias'])] = $adapterClass;
        
        return $this;
    }

    /**
     * @throws Exception
     */
    public function use(string $alias): AbstractGatewayAdapter
    {
        if (!isset($this->adapters[$alias])) {
            throw new InvalidArgumentException(
                sprintf('Gateway adapter for the alias "%s" is not registered', $alias),
            );
        }
        
        if (!$this->adapters[$alias] instanceof AbstractGatewayAdapter) {
            $this->adapters[$alias] = $this
                ->container
                ->get($this->adapters[$alias]);
        }
        
        return $this->adapters[$alias];
    }

    private function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    private function __clone()
    {
    }
}
