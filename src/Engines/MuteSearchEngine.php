<?php

declare(strict_types=1);

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\ErrorHandlerInterface;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;

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
     *             new Client(),
     *             new ResponseFromGeoDtoMapper()
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

    public function search(string $query): ?GeoDto
    {
        try {
            return $this->next->search($query);
        } catch (\Exception $e) {
            $this->handler->handle($e);
        }

        return null;
    }
}
