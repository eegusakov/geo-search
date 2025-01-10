<?php

declare(strict_types=1);

namespace GeoSearch\Handlers;

use GeoSearch\Interfaces\ErrorHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Base class for error handling.
 */
final readonly class ErrorHandler implements ErrorHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function handle(\Throwable $exception): void
    {
        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
        ]);
    }
}
