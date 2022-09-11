<?php
declare(strict_types=1);

namespace Symbiotic\Http\Cookie;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;



class CookiesMiddleware implements MiddlewareInterface
{

    protected CookiesInterface $cookies;

    protected int $expires = 72000;

    public function __construct(ContainerInterface $container)
    {
        $this->cookies = $container->get(CookiesInterface::class);

        $config = $container->get('config');
        $this->expires = $config->has('cookie_expires') ? (int)$config->get('cookie_expires'): 3600 * 24 * 365;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $this->cookies->setDefaults(
            $request->getUri()->getHost(),
            '/',
            $this->expires,
            $request->getUri()->getScheme() === 'https'
        );

        $this->cookies->setRequestCookies($request->getCookieParams());
        $response = $handler->handle($request);
        return $this->cookies->toResponse($response);
    }
}