<?php

namespace App\Http\Middleware;

use Core\Cache as CacheFile;

/**
 * Cache
 */
class Cache
{
    /**
     * If the cache is enabled, and the request method is GET, and the request header does not contain
     * a no-cache directive, then the request is cacheable
     *
     * @param request The request object.
     * @return The return value is a boolean value.
     */
    private function isCacheable($request)
    {
        //check if cache is enabled by time
        if ($_ENV['CACHE_TIME'] <= 0) {
            return false;
        }

        //check request method
        if ($request->getHttpMethod() != 'GET') {
            return false;
        }

        //check header cache
        if ($_ENV['CACHE_CONTROL'] == 'true') {
            $headers = $request->getHeaders();
            if (isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache') {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate a hash based on the URI and query parameters
     *
     * @param request The request object.
     * @return The hash of the URI and query parameters.
     */
    private function getHash($request)
    {
        //uri route
        $uri = $request->getRouter()->getUri();

        //query params
        $queryParams = $request->get();
        $uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : '';

        //clear uri and return hash
        return rtrim('route-' . preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/')), '-');
    }

    /**
     * If the request is cacheable, then return the cached data. Otherwise, return the data from the
     * next middleware
     *
     * @param request The current request object.
     * @param next The next callable in the middleware chain.
     *
     * @return The cache file object.
     */
    public function handle($request, $next)
    {
        //check if current request is cacheable
        if (!$this->isCacheable($request)) {
            return $next($request);
        }

        //cash hash
        $hash = $this->getHash($request);

        //return cache data
        return CacheFile::getCache($hash, $_ENV['CACHE_TIME'], function () use ($request, $next) {
            return $next($request);
        });
    }
}
