<?php


namespace Enkeltinnhold;
use Enkeltinnhold;

class Page extends Enkeltinnhold\Base {
    protected $resolved = false;
    protected $pageKeyPrefix = 'page:';
    protected $pageData;

    public function resolvePage() {
        $request = trim(strip_tags($_SERVER['QUERY_STRING']));
        $pageKey = false;

        if(mb_strlen($request) == 0) {
            // Index
            $pageKey = $this->pageKeyPrefix.'index';
        } else {
            $request = explode('/', $request);
            if(is_array($request)) {
                $request = explode('=', $request[0]);
                if(isset($request[1])) {
                    $pageKey = $this->pageKeyPrefix.$request[1];
                }
            }
        }

        //debug($this->getRedisClient()->hset($this->getMasterKey(), $pageKey, "TEST")); // returnerer false hvis field allerede fantes...
        //debug($this->getRedisClient()->hset($this->getMasterKey(), 'page:reserved:404', "<h1>4-oh-noes-4!</h1>")); // returnerer false hvis field allerede fantes...

        // 47brygg:page:1
        // 47brygg:page:reserved:404 pageData
        // må bygge opp et "set" med page keys.

        // @todo: Lese om sets og scan.

        //$this->getRedisClient()->hset('47brygg:page:reserved:404', 'pageData', '<html>')
        //$this->getRedisClient()->hset('47brygg:page:1')
        // http://stackoverflow.com/questions/19910527/how-to-use-hscan-command-in-redis svar 2

        $pageData = $this->getRedisClient()->hget($this->getMasterKey(), $pageKey);

        if($pageData !== NULL) {
            $this->resolved = true;
        } else {
            $this->resolved = false;
            $pageKey = $this->pageKeyPrefix.'reserved:404';
            $pageData = $this->getRedisClient()->hget($this->getMasterKey(), $pageKey);
        }
        $this->pageData = $pageData;
        return $this->resolved;
    }

    public function getPageData() {
        return $this->pageData; // @todo: masse greier - dele opp, legge til element for element i admin.
    }

    public function sendHeaders($httpCode = false) {
        // Default to false, let nginx handle.

        header("Cache-Control: no-cache, must-revalidate", true); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", true); // Date in the past

        switch($httpCode) {
            case 404:
                //header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", false, 404); // @todo: nginx tar over 404...
                break;
        }
    }
}