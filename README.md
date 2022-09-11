# Symbiotic Http Cookie
README.RU.md  [РУССКОЕ ОПИСАНИЕ](https://github.com/Symbiotic-php/http-cookie/blob/master/README.RU.md)
## Features

- Accepts basic settings for installing cookies (domain, path, secure, expires, etc.)
- No extra code and very light (9 Kb)
- Compatible with PSR-15, PSR-7
- Included PSR-15 Cookie Middleware
- Has no dependencies, only PSR interfaces
- You can work as with an array (ArrayAccess)
- Нет приватных свойств и методов


## Installation
```
composer require Symbiotic/http-cookie 
```

## Using

For easier operation, the package has a ready-made PSR-15 Middleware,
which itself will accept cookies from the request and send them in response:

\Symbiotic\Http\Cookie\CookiesMiddleware


##### Initialization
```php

$cookies = new \Symbiotic\Http\Cookie\Cookies();

// Setting default values
$domain  = 'example.com';
$path    = '/';
$expires = time() + (3600 * 24 * 365);
$secure = true; // only https
// @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#attributes
$same_site = \Symbiotic\Http\Cookie\CookiesInterface::SAMESITE_LAX;

$cookies->setDefaults($domain, $path, $expires, $secure, $same_site);

/**
 * Installing cookies from a request with the ServerRequestInterface
 *
 * @var \Psr\Http\Message\ServerRequestInterface $request
 * @var string[] $request_cookies ['name' => 'value',...]
**/
$request_cookies = $request->getCookieParams();
$cookies->setRequestCookies($request_cookies);

/**
 * Or native
 **/
$cookies->setRequestCookies($_COOKIE);

```

##### Getting and checking availability

```php

$cookies = new Symbiotic\Http\Cookie\Cookies();

/**
 * Checking for the presence of a cookie from the request, a cookie with an empty value also exists
 */
$cookies->has('cookie_name'); // return bool 

/**
 * Getting cookie by name
 * If the cookie does not exist it will return null
**/
$cookies->get('cookie_name'); // return string or null 

/**
 * Getting a cookie with a default value, if the cookie does not exist.
**/
$cookies->get('cookie_name', 'default_value'); // return string  

```

##### Setting the response cookie

```php

$cookies = new \Symbiotic\Http\Cookie\Cookies();

/**
 * Short way to add cookies
 * the remaining parameters are taken from the default values set in the method:
 * @see \Symbiotic\Http\Cookie\Cookies::setDefaults()
 */
$cookies->set('cookie_name', 'cookie value');

/**
 * Advanced installation method, allows you to pass any parameters
 * if you pass null to some parameters, they will be taken from the default ones
 */
$domain  = 'www.example.com';
$path    = '/docs';
$expires = time()+300; // timestamp for 5 minutes ahead
$secure = false; // only https
$http_only = true; // only http request access
// advanced params
$options = [
    'same_site' => \Symbiotic\Http\Cookie\CookiesInterface::SAMESITE_STRICT,
    'max_age' =>1000
];

$cookies->setCookie('cookie_name', 'cookie value', $expires, $http_only, $path, $domain, $secure, $options);

```


##### Deleting cookies

```php

$cookies = new \Symbiotic\Http\Cookie\Cookies();

/**
 * Short way to delete cookies
 * the remaining parameters are taken from the default values set in the method:
 * @see \Symbiotic\Http\Cookie\Cookies::setDefaults()
 */
$cookies->remove('cookie_name');


/**
 * Deleting multiple cookies at once
 */
$delete_cookies = [
    'name1',
    'name2',
    'name3'
];
$cookies->remove($delete_cookies);



/**
 * Advanced deletion method, allows you to pass any parameters
 * if you pass null to some parameters, they will be taken from the default ones
 */
$domain  = 'www.example.com';
$path    = '/docs';
$expires =  time()-(60*60*48); // outdated timestamp!!!
$cookies->setCookie('cookie_name', '', $expires, null, $path, $domain);

```

### Working as with an array (ArrayAccess)
For easier data access and easier cookie installation, the class implements the \ArrayAccess interface.


```php

$cookies = new \Symbiotic\Http\Cookie\Cookies();

/**
 * Getting a cookie from a request
 * @see \Symbiotic\Http\Cookie\Cookies::get($key)
 */
$value = $cookies['cookie_name']; // string|array or null if not exists

/**
 * Setting a cookie in response 
 * (the domain and other parameters are taken from the default ones)
 * @see \Symbiotic\Http\Cookie\Cookies::set($key, $value)
 */
$cookies['new_cookie'] = 'value';

/**
 * Checking the existence of a cookie from a request
 * @see \Symbiotic\Http\Cookie\Cookies::has($key)
 */
$exists  = isset($cookies['new_cookie']);



/**
 * Delete cookie
 * @see \Symbiotic\Http\Cookie\Cookies::remove($key)
 */
unset($cookies['cookie_name']);


```





