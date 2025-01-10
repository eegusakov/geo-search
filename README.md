# Geo Search

![GitHub](https://img.shields.io/github/license/eegusakov/geo-search)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/eegusakov/geo-search)
[![CI](https://github.com/eegusakov/geo-search/actions/workflows/ci.yml/badge.svg)](https://github.com/eegusakov/geo-search/actions/workflows/ci.yml)
[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/eegusakov/geo-search/php)](https://packagist.org/packages/eegusakov/geo-search)

Language: ENG, [RUS](docs/ru/README.md)

**Geo Search** - PHP library that will allow you to determine the geographical location of an object based on the transmitted data.

Third-party service APIs are used for the search, so the input data for the search may differ depending on the service. The entire list of available services is listed below.

## Compatible with PSR
Any client compatible with PSR-18 is suitable for providing http requests. The examples will use the library [Guzzle](https://github.com/guzzle/guzzle)

Any client compatible with PSP-16 is suitable for working with the cache. The examples will use libraries [SymfonyCache](https://github.com/symfony/cache)

Any client compatible with PSR-3 is suitable for error logging ([Monolog](https://github.com/Seldaek/monolog ) and so on.). The examples will use the ConsoleLogger included in the current library.

## Getting started

### Installing Geo Search

The recommended way to install Geo Search is via
[Composer](http://getcomposer.org/).

```bash
composer require eegusakov/geo-search
```

### Services
Attention, to work with each service, you need to get an API access token. To get it, follow the link attached to a specific service

#### 1. WeatherApi
Link to the service: https://www.weatherapi.com/my/

Link to documentation: https://www.weatherapi.com/api-explorer.aspx#tz

Performs a search on the following data:
1. US postal code,
2. UK postal code,
3. postal code of Canada,
4. IP address,
5. latitude/longitude (decimal degree),
6. name of the city;

Example:

```php
use GuzzleHttp\Client;
use GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;
use GeoSearch\Engines\WeatherApi\ResponseFromGeoDtoMapper;

$weatherApiSearchEngine = new WeatherApiSearchEngine(
    '<API_TOKEN>',
    new Client()
);

$geoByCity = $weatherApiSearchEngine->search('Moscow');

$geoByCoordinates = $weatherApiSearchEngine->search('53,-0.12');

$geoByZipCode = $weatherApiSearchEngine->search('90201');
```

#### 2. OpenMeteo
Link to the service: https://open-meteo.com/

Link to documentation: https://open-meteo.com/en/docs/geocoding-api

Performs a search on the following data:
1. name of the city,
2. postal code;

Example:

```php
use GuzzleHttp\Client;
use GeoSearch\Engines\OpenMeteo\OpenMeteoSearchEngine;

$openMeteoSearchEngine = new OpenMeteoSearchEngine(
  new Client()
);

$geoByCity = $openMeteoSearchEngine->search('Moscow');

$geoByZipCode = $openMeteoSearchEngine->search('10001');
```

### Additional features

**1. Using multiple services at once**

This library allows you to use several services at once and get the result of the first service that returned a non-empty response.

```php
use GeoSearch\Engines\ChainSearchEngine;
use GeoSearch\Engines\OpenMeteo\OpenMeteoSearchEngine;
use GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;
use GuzzleHttp\Client;

$chainSearchEngine = new ChainSearchEngine(
    new WeatherApiSearchEngine(
        '<API_TOKEN_1>',
        new Client()
    ),
    new WeatherApiSearchEngine(
        '<API_TOKEN_2>',
        new Client()
    ),
    new OpenMeteoSearchEngine(
        new Client(),
    )
);

$geo = $chainSearchEngine->search('Moscow');
```

**2. The ability to ignore errors**

The functionality is relevant when there is a need to ignore errors and move on to the next request.

ErrorHandler processes all errors and writes them to the log, the library includes a basic logger that simply outputs information to the console (ConsoleLogger).

Any client compatible with PSR-3 is suitable for logging.

```php
use GuzzleHttp\Client;
use GeoSearch\Engines\MuteSearchEngine;
use GeoSearch\Handlers\ErrorHandler;
use GeoSearch\Loggers\ConsoleLogger;
use GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;

$muteSearchEngine = new MuteSearchEngine(
    new WeatherApiSearchEngine(
        '<API_TOKEN>',
        new Client()
    ),
    new ErrorHandler(
        new ConsoleLogger()
    )
);

$geo = $muteSearchEngine->search('Moscow');
```

**3. Ability to cache the response result**

Any client compatible with PSP-16 is suitable for working with the cache. The example will use [SymfonyCache](https://symfony.com/doc/current/components/cache.html).

```php
use GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use GeoSearch\Engines\CacheSearchEngine;
use Symfony\Component\Cache\Psr16Cache;
use GuzzleHttp\Client;

$cacheSearchEngine = new CacheSearchEngine(
    new WeatherApiSearchEngine(
        '<API_TOKEN>',
        new Client()
    ),
    new Psr16Cache(
        new FilesystemAdapter()
    ),
    60
);

$geo = $cacheSearchEngine->search('Moscow');
```

**4. The ability to combine the 1st, 2nd and 3rd item**

```php
use GuzzleHttp\Client;
use Symfony\Component\Cache\Psr16Cache;
use GeoSearch\Handlers\ErrorHandler;
use GeoSearch\Loggers\ConsoleLogger;
use GeoSearch\Engines\MuteSearchEngine;
use GeoSearch\Engines\CacheSearchEngine;
use GeoSearch\Engines\ChainSearchEngine;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use GeoSearch\Engines\OpenMeteo\OpenMeteoSearchEngine;
use GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;

$cacheChainMuteSearchEngine = new CacheSearchEngine(
    new ChainSearchEngine(
        new MuteSearchEngine(
            new WeatherApiSearchEngine(
                'API_TOKEN_1',
                new Client()
            ),
            new ErrorHandler(
                new ConsoleLogger()
            )
        ),
        new MuteSearchEngine(
            new OpenMeteoSearchEngine(
                new Client()
            ),
            new ErrorHandler(
                new ConsoleLogger()
            )
        )
    ),
    new Psr16Cache(
        new FilesystemAdapter()
    ),
    60
);

$geo = $cacheChainMuteSearchEngine->search('Moscow');
```

## Cooperation

Please read [CONTRIBUTING](CONTRIBUTING.md ) for more information about our code of conduct and the process of sending us merge requests.

## License

This project is licensed under the MIT license - see the [LICENSE](LICENSE.md) file for details
