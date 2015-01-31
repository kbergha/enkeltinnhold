<?php


namespace Enkeltinnhold;

class Page extends Base {
    private $resolved = false;
    private $pageKeyPrefix = 'page:';
    private $pageData;

    public function resolvePage() {
        $request = trim(strip_tags($_SERVER['QUERY_STRING']));
        $pageKey = false;

        $predisClient = $this->getPredisClient();

        if(mb_strlen($request) == 0) {
            // Index
            $pageKey = $this->pageKeyPrefix.'reserved:index';
        } else {
            $request = explode('/', $request);
            if(is_array($request)) {
                $request = explode('=', $request[0]);
                if(isset($request[1])) {
                    $pageKey = $this->pageKeyPrefix.$request[1];
                }
            }
        }


        /*
         *
         * created
         * updated
         * title
         *
         *
         * */


        // 47brygg:page:1
        // 47brygg:page:reserved:404 pageData

        // http://stackoverflow.com/questions/19910527/how-to-use-hscan-command-in-redis svar 2

        //$predisClient->hset('47brygg:page:reserved:404', 'pageData', '<h1>4-oh-noes-4!</h1>');
        //debug($predisClient->hset('47brygg:page:2', 'pageData', "<h1>#2 - Lucky Jack</h1><p>Malt, humle og kjærlighet</p>"));

        //SADD 47brygg:allpages
        //debug($predisClient->sadd('47brygg:allpages', array("page:1", "page:2", "page:reserved:index", "page:reserved:404")));

        // set is member
        if($predisClient->sismember($this->getMasterKey().':allpages', $pageKey)) {
            $this->resolved = true;
            $this->pageData = $predisClient->hget($this->getMasterKey().':'.$pageKey, "pageData");
        } else {
            $pageKey = 'page:reserved:404';
            $this->pageData = $predisClient->hget($this->getMasterKey().':'.$pageKey, "pageData");
        }

        return $this->resolved;
    }

    public function getPageData() {
        return $this->pageData; // @todo: masse greier - dele opp, legge til element for element i admin.
    }

    public function isResolved() {
        return $this->resolved;
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

    public function getAllPageKeys() {
        $predisClient = $this->getPredisClient();
        $allPages = array();
        $pages = $predisClient->smembers($this->getMasterKey().':allpages');
        if(is_array($pages) && count($pages)) {
            foreach($pages as $page) {
                if(stripos($page, 'page:reserved:') === false) {
                    $allPages[] = $page;
                }
            }
        }
        return $allPages;

    }

    public function getAllReservedPageKeys() {
        $predisClient = $this->getPredisClient();
        $allPages = array();
        $pages = $predisClient->smembers($this->getMasterKey().':allpages');
        if(is_array($pages) && count($pages)) {
            foreach($pages as $page) {
                if(stripos($page, 'page:reserved:') === 0) {
                    $allPages[] = $page;
                }
            }
        }
        return $allPages;
    }
}