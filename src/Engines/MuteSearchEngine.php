<?php

declare(strict_types=1);

namespace GeoSearch\Engines;

use GeoSearch\Dto\GeoDto;
use GeoSearch\Interfaces\ErrorHandlerInterface;
use GeoSearch\Interfaces\SearchEngineInterface;

/**
 * A class that allows you to ignore exceptions that occurred when searching for geographic objects.
 */
final class MuteSearchEngine implements SearchEngineInterface
{
    /**
     * MuteGeoSearch accepts a search engine whose errors must be ignored.
     *
     * Here is an example of creating a geo search with ignoring errors:
     *
     *     $geoSearchMute = new MuteGeoSearch(
     *         new WeatherApiGeoSearch(
     *             '<API_TOKEN>',
     *             new Client()
     *         ),
     *         new ErrorHandler(
     *             new ConsoleLogger()
     *         )
     *     );
     */
    public function __construct(
        private SearchEngineInterface $next,
        private ErrorHandlerInterface $handler
    ) {}

    /**
     * @return array<empty>|GeoDto[]
     */
    public function search(string $query): array
    {
        try {
            return $this->next->search($query);
        } catch (\Exception $e) {
            $this->handler->handle($e);
        }

        return [];
    }
}
