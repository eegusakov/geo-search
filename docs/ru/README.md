# Geo Search

![GitHub](https://img.shields.io/github/license/eegusakov/geo-search)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/eegusakov/geo-search)
[![CI](https://github.com/eegusakov/geo-search/actions/workflows/ci.yml/badge.svg)](https://github.com/eegusakov/geo-search/actions/workflows/ci.yml)
[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/eegusakov/geo-search/php)](https://packagist.org/packages/eegusakov/geo-search)

Язык: Русский, [Английский](README.md)

**Geo Search** - PHP библиотека, которая позволит вам определить географическое расположение объекта, на основании переданных данных.

Для поиска используются API сторонних сервисов, поэтому входные данные для поиска могут отличаться в зависимости от сервиса. Весь перечень доступных сервисов указан ниже.

## Совместимость с PSR
Для обеспечения http запросов подойдет любой клиент, совместимый с PSR-18. В примерах будет использоваться библиотека [Guzzle](https://github.com/guzzle/guzzle)

Для обеспечения работы с кэшем подойдет любой клиент, совместимый с PSR-16. В примерах будет использоваться библиотек [SymfonyCache](https://github.com/symfony/cache)

Для логирования ошибок подойдет любой клиент, совместимый с PSR-3 ([Monolog](https://github.com/Seldaek/monolog) и тд.). В примерах будет использоваться, входящий в текущую библиотеку, ConsoleLogger.

## Приступая к работе

### Установка Geo Search

Рекомендуемый способ установки Geo Search — через
[Composer](https://getcomposer.org/).

```bash
composer require eegusakov/geo-search
```

### Сервисы
Внимание, для работы с каждым сервисов необходимо получить токен доступа к API. Для его получения перейдите по ссылке приложенной к конкретному сервису

#### 1. WeatherApi
Ссылка на сервис: https://www.weatherapi.com/my/

Ссылка на документацию: https://www.weatherapi.com/api-explorer.aspx#tz

Производит поиск по следующим данным:
1. почтовый индекс США,
2. почтовый индекс Великобритании,
3. почтовый индекс Канады,
4. IP-адрес,
5. широта/долгота (десятичный градус)
6. название города

Пример:

```php
use GuzzleHttp\Client;
use GeoSearch\Engines\WeatherApi\WeatherApiSearchEngine;

$weatherApiSearchEngine = new WeatherApiSearchEngine(
    '<API_TOKEN>',
    new Client()
);

$geoByCity = $weatherApiSearchEngine->search('Москва');

$geoByCoordinates = $weatherApiSearchEngine->search('53,-0.12');

$geoByZipCode = $weatherApiSearchEngine->search('90201');
```

#### 2. OpenMeteo
Ссылка на сервис: https://open-meteo.com/

Ссылка на документацию: https://open-meteo.com/en/docs/geocoding-api

Производит поиск по следующим данным:
1. название города
2. почтовый код

Пример:

```php
use GuzzleHttp\Client;
use GeoSearch\Engines\OpenMeteo\OpenMeteoSearchEngine;

$openMeteoSearchEngine = new OpenMeteoSearchEngine(
  new Client()
);

$geoByCity = $openMeteoSearchEngine->search('Москва');

$geoByZipCode = $openMeteoSearchEngine->search('10001');
```

### Дополнительные возможности

**1. Использование сразу несколько сервисов**

Данная библиотека позволяет использовать сразу несколько сервисов и получить результат первого сервиса вернувшего не пустой ответ.

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
        new Client()
    )
);

$geo = $chainSearchEngine->search('Москва');
```

**2. Возможность игнорирования ошибок**

Функционал актуален, когда есть необходимость игнорировать ошибки и переходить к следующему запросу.

ErrorHandler обрабатывает все ошибки и записывает их в лог, в библиотеке включен базовый logger, который просто выводит информацию в консоль (ConsoleLogger).

Для логирования подойдет любой клиент, совместимый с PSR-3.

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

$geo = $muteSearchEngine->search('Москва');
```

**3. Возможность кэшировать результат ответа**

Для работы с кэшем подойдет любой клиент, совместимый с PSR-16. В примере будет использоваться [SymfonyCache](https://symfony.com/doc/current/components/cache.html).

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

$geo = $cacheSearchEngine->search('Москва');
```

**4. Возможность комбинировать 1-й, 2-й и 3-й пункт**

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

$geo = $cacheChainMuteSearchEngine->search('Москва');
```

## Сотрудничество

Пожалуйста, прочтите [CONTRIBUTING](../../CONTRIBUTING.md) для получения подробной информации о нашем кодексе поведения и процессе отправки нам запросов на слияния.

## Лицензия

Этот проект лицензирован по лицензии MIT - смотрите [LICENSE](../../LICENSE.md) файл для получения подробной информации
