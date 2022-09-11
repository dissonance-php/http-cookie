<?php

declare(strict_types=1);

namespace Symbiotic\Http\Cookie;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


interface CookiesInterface extends \ArrayAccess
{
    const COOKIE_HEADER = 'Cookie';
    const SET_COOKIE_HEADER = 'Set-Cookie';

    const SAMESITE_NONE = 'None';
    const SAMESITE_LAX = 'Lax';
    const SAMESITE_STRICT = 'Strict';

    const SAMESITE_VALUES = [
        self::SAMESITE_NONE,
        self::SAMESITE_LAX,
        self::SAMESITE_STRICT
    ];

    /**
     * Set global values for cookies
     *
     * @param string|null $domain
     * @param bool|null   $secure
     * @param int|null    $expires
     * @param string|null $path
     * @param string|null $same_site
     *
     * @return void
     */
    public function setDefaults(
        string $domain = null,
        string $path = null,
        int $expires = null,
        bool $secure = null,
        string $same_site = null
    ): void;

    /**
     * Installing cookies from the request for further work
     *
     * @param array $cookies - [name => value,...]
     *
     * @see     ServerRequestInterface::getCookieParams()
     * @used-by CookiesMiddleware::process()
     */
    public function setRequestCookies(array $cookies): void;

    /**
     * @return array[]
     * @uses \Symbiotic\Http\Cookie\Cookies::$items
     * @see  CookiesInterface::setCookie()
     * [
     *   0 => ['name' =>'c_name','value' => 'val','domain' =>'domain.com','path' => '/',..],
     *     ...
     * ]
     */
    public function getResponseCookies(): array;

    /**
     * @param string      $name
     * @param string      $value
     * @param int|null    $expires
     * @param bool|null   $httponly
     * @param string|null $path
     * @param string|null $domain
     * @param bool|null   $secure
     * @param array       $options
     * set same_site as key , allowed values {@see CookiesInterface::SAMESITE_VALUES}
     * set max_age as key - Max-Age cookie param in value
     *
     * @return array|\ArrayAccess
     */
    public function setCookie(
        string $name,
        string $value = '',
        int $expires = null,
        bool $httponly = null,
        bool $secure = null,
        string $path = null,
        string $domain = null,
        array $options = []
    ): \ArrayAccess|array;

    /**
     * Sending the set cookies to the response headers
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function toResponse(ResponseInterface $response): ResponseInterface;

    /**
     * Sending the set cookies to the response headers
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function toRequest(RequestInterface $request): RequestInterface;


    /**
     * Checks if an a key is present and not null.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Getting cookies by name
     *
     * @param string      $name
     * @param string|null $default
     *
     * @return string|array|null - array if setted 'cookie_name[key]' and getting 'cookie_name'
     * @link  https://www.php.net/manual/ru/function.setcookie.php -  array cookie in doc!
     */
    public function get(string $name, string $default = null): array|string|null;

    /**
     * Setting a cookie with default settings
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function set(string $name, string $value = ''): void;

    /**
     * Deleting cookies, takes a name or an array of names
     *
     * @param string|string[] $names
     *
     * @return void
     */
    public function remove(array|string $names): void;
}