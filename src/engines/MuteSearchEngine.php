<?php

namespace Eegusakov\GeoSearch\Engines;

use Eegusakov\GeoSearch\Dto\GeoDto;
use Eegusakov\GeoSearch\Interfaces\ErrorHandlerInterface;
use Eegusakov\GeoSearch\Interfaces\SearchEngineInterface;
use Exception;

/**
 * A class that allows you to ignore exceptions that occurred when searching for geographic objects.
 */
class MuteSearchEngine implements SearchEngineInterface
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
     *
     * @param SearchEngineInterface $next
     * @param ErrorHandlerInterface $handler
     */
    public function __construct(
        private SearchEngineInterface $next,
        private ErrorHandlerInterface $handler
    ) {
    }

    /**
     * @param string $query
     * @return GeoDto|null
     */
    public function search(string $query): ?GeoDto
    {
        try {
            return $this->next->search($query);
        } catch (Exception $e) {
            $this->handler->handle($e);
        }

        return null;
    }
}