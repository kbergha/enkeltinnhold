<?php
namespace Enkeltinnhold;

class Base {

    protected $masterKey = null;

    public function __construct() {
        global $siteConfig; // @todo: make a singleton or something instead?...
        $this->masterKey = $siteConfig['masterKey'];
    }

    public function getMasterKey() {
        return $this->masterKey;
    }

    public function getPredisClient() {
        global $predisClient;
        return $predisClient;
    }

    public function getPage() {
        global $page;
        return $page;
    }

    public function getSiteConfig() {
        global $siteConfig;
        return $siteConfig;
    }
}