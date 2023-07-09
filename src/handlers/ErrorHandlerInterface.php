<?php

namespace Eegusakov\GeoSearch\Handlers;

/**
 * Interface describing the structure of error handlers
 */
interface ErrorHandlerInterface
{
    /**
     * The main method with the logic of error handling
     *
     * @param \Exception $exception
     * @return void
     */
    public function handle(\Exception $exception): void;
}