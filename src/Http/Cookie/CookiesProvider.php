<?php
declare(strict_types=1);

namespace Symbiotic\Http\Cookie;

use Symbiotic\Core\CoreInterface;
use Symbiotic\Http\Middleware\MiddlewaresDispatcher;
use Symbiotic\Core\ServiceProvider;
use Psr\Http\Message\ServerRequestInterface;


class CookiesProvider extends ServiceProvider
{
    public function register(): void
    {
        $app = $this->app;
        $app->live(CookiesInterface::class, function (CoreInterface $app) {
            // $request = $app[ServerRequestInterface::class];
            $expires = $app('config::cookie_expires', 3600 * 24 * 365);
            $cookies = $this->factoryCookiesClass();
            /*  if ($request instanceof ServerRequestInterface) {
                  $cookies->setDefaults(
                      $request->getUri()->getHost(),
                      '/',
                      $expires,
                      $request->getUri()->getScheme() === 'https'
                  );
              } else {*/
            $cookies->setDefaults($app['config::default_host'], '/', $expires);
            /*  }*/
            return $cookies;
        },         'cookie');

        $app['listeners']->add(MiddlewaresDispatcher::class, static function (MiddlewaresDispatcher $event) {
            $event->prependToGroup(
                MiddlewaresDispatcher::GROUP_GLOBAL,
                CookiesMiddleware::class
            );
        });
    }

    protected function factoryCookiesClass(): CookiesInterface
    {
        return new Cookies();
    }
}