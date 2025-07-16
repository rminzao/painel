<?php

namespace Core;

class Cache
{
    /**
     * Return file cache path
     * @method getFilePath
     * @param  string      $hash
     * @return string
     */
    private static function getFilePath($hash)
    {
        //path temp cache
        $dir = realpath(__DIR__ . '/../storage/framework/cache');

        //check if path exist
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        //return path cache file
        return $dir . '/' . hash('ripemd160', $hash);
    }

    /**
     * Save data on cache
     * @method storageCache
     * @param  string      $hash
     * @param  mixed       $content
     * @return boolean
     */
    private static function storageCache($hash, $content)
    {
        //serialize content
        $serialize = serialize($content);

        //get path from cache file
        $cacheFile = self::getFilePath($hash);

        //write content on cache file
        return file_put_contents($cacheFile, $serialize);
    }

    /**
     * Return content from cache
     * @method getContentCache
     * @param string $hash
     * @param integer $expiration
     * @return mixed
     */
    private static function getContentCache($hash, $expiration)
    {
        //get file path
        $cacheFile = self::getFilePath($hash);

        //check if file exist
        if (!file_exists($cacheFile)) {
            return false;
        }

        //check expired cache
        $createTime = filemtime($cacheFile);
        $diffTime = time() - $createTime;
        if ($diffTime > $expiration) {
            return false;
        }

        //return real data from cache
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);
    }

    /**
     * Get info from cache
     * @method getCache
     * @param string $hash
     * @param integer $expiration
     * @param \Closure $function
     * @return mixed
     */
    public static function getCache($hash, $expiration, $function)
    {
        //check if exist content on cache
        if ($content = self::getContentCache($hash, $expiration)) {
            return $content;
        }

        //exec function
        $content = $function();

        //writes the return in cache
        self::storageCache($hash, $content);

        //return content
        return $content;
    }
}
