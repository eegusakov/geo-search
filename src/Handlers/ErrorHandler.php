<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Handlers;

use Eegusakov\GeoSearch\Interfaces\ErrorHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Base class for error handling.
 */
final class ErrorHandler implements ErrorHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function handle(\Exception $exception): void
    {
        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
        ]);
    }
}
