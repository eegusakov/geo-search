<?php

namespace Eegusakov\GeoSearch\Handlers;

use Eegusakov\GeoSearch\Interfaces\ErrorHandlerInterface;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Base class for error handling
 */
class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param Exception $exception
     * @return void
     */
    public function handle(Exception $exception): void
    {
        $this->logger->error($exception->getMessage(), [
            'exception' => $exception
        ]);
    }
}