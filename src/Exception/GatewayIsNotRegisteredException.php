<?php

declare(strict_types=1);

namespace Creatortsv\OmnipayManagerBundle\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class GatewayIsNotRegisteredException extends Exception
{
    public function __construct(string $alias)
    {
        $message = sprintf('Gateway adapter for the alias "%s" is not registered', $alias);

        parent::__construct($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
