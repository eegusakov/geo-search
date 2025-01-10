<?php

declare(strict_types=1);

namespace GeoSearch\Interfaces;

/**
 * Interface describing the structure of error handlers.
 */
interface ErrorHandlerInterface
{
    /**
     * The main method with the logic of error handling.
     */
    public function handle(\Throwable $exception): void;
}
