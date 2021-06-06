# Dissonance Http Cookie

## Характеристики

- Принимает базовые настройки для установки куков (domain, path, secure, expires и т.д.)
- Без лишнего кода и очень легкий (9 Kb)
- Совместим с PSR-15, PSR-7
- В комплекте PSR-15 Middleware
- Не имеет зависимостей, кроме PSR интерфейсов
- Доступна работа как с массивом (ArrayAccess)
- No private properties and methods


## Установка
```
composer require dissonance/http-cookie 
```

## Использование

Для более простой работы в пакете есть готовая PSR-15 Middleware, 
которая сама примет из запроса куки и отправит отправит установленные в ответ:

\Dissonance\Http\Cookie\CookiesMiddleware


##### Инициализация
```php

$cookies = new \Dissonance\Http\Cookie\Cookies();

// Установка дефолтных значений
$domain  = 'example.com';
$path    = '/';
$expires = time() + (3600 * 24 * 365);
$secure = true; // only https
// @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#attributes
$same_site = \Dissonance\Http\Cookie\CookiesInterface::SAMESITE_LAX;

$cookies->setDefaults($domain, $path, $expires, $secure, $same_site);

/**
 * Установка кук из запроса с ServerRequestInterface
 *
 * @var \Psr\Http\Message\ServerRequestInterface $request
 * @var string[] $request_cookies ['name' => 'value',...]
**/
$request_cookies = $request->getCookieParams();
$cookies->setRequestCookies($request_cookies);

/**
 * Или нативно
 **/
$cookies->setRequestCookies($_COOKIE);

```

##### Получение и проверка наличия 

```php

$cookies = new Dissonance\Http\Cookie\Cookies();

/**
 * Проверка наличия куки из запроса, кука с пустым значением тоже считается
 */
$cookies->has('cookie_name'); // return bool 

/**
 * Получение куки
 * Если куки не существует вернет null
**/
$cookies->get('cookie_name'); // return string or null 

/**
 * Получение куки c дефолтным значением, если куки нет
**/
$cookies->get('cookie_name', 'default_value'); // return string  

```

##### Устанока кук

```php

$cookies = new \Dissonance\Http\Cookie\Cookies();

/**
 * Короткий способ добавления куки, остальные параметры берутся из дефолтных значений , установленных в методе
 * @see \Dissonance\Http\Cookie\Cookies::setDefaults()
 */
$cookies->set('cookie_name', 'cookie value');

/**
 * Расширенный способ, позволяет передать любые параметры,
 * если передавать null в некоторые параметры, то будут браться из установленных по умолчанию
 */
$domain  = 'www.example.com';
$path    = '/docs';
$expires = time()+300; // метка времени на 5 минут вперед
$secure = false; // only https
$http_only = true; // only http request access
$options = ['samesite' => \Dissonance\Http\Cookie\CookiesInterface::SAMESITE_STRICT]; // advanced params
$cookies->setCookie('cookie_name', 'cookie value', $expires, $http_only, $path, $domain, $secure, $options);

```


##### Удаление кук

```php

$cookies = new \Dissonance\Http\Cookie\Cookies();

/**
 * Короткий способ удаления куки, остальные параметры берутся из дефолтных значений , установленных в методе
 * @see \Dissonance\Http\Cookie\Cookies::setDefaults()
 */
$cookies->remove('cookie_name');


/**
 * Удаление сразу нескольких кук
 */
$delete_cookies = [
    'name1',
    'name2',
    'name3'
];
$cookies->remove($delete_cookies);



/**
 * Расширенный способ, позволяет передать любые параметры,
 * если передавать null в некоторые параметры, то будут браться из установленных по умолчанию
 */
$domain  = 'www.example.com';
$path    = '/docs';
$expires =  time()-(60*60*48); // устаревшая метка времени 
$cookies->setCookie('cookie_name', '', $expires, null, $path, $domain);

```

### Работа как с массивом (ArrayAccess)
Для более удобного доступа к данным и простой установки кук Класс имплементирует интерфейс \ArrayAccess


```php

$cookies = new \Dissonance\Http\Cookie\Cookies();

/**
 * Получение куки из запроса
 * @see \Dissonance\Http\Cookie\Cookies::get($key)
 */
$cookies['cookie_name']; // string or null 

/**
 * Установка куки  
 * (домен и остальные параметры берутся из дефолтных)
 * @see \Dissonance\Http\Cookie\Cookies::set($key, $value)
 */
$cookies['new_cookie'] = 'value';

/**
 * Проверка сущесвования куки из запроса
 * @see \Dissonance\Http\Cookie\Cookies::has($key)
 */
$exists  = isset($cookies['new_cookie']);



/**
 * Удаление куки
 * @see \Dissonance\Http\Cookie\Cookies::remove($key)
 */
unset($cookies['cookie_name']);


```





