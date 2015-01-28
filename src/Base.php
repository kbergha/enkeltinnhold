<?php
namespace Enkeltinnhold;

class Base {

    protected $masterKey = null;
    protected $predisClient = null;

    public function __construct() {
        global $siteConfig, $predisClient; // @todo: make a singleton or something instead...
        $this->masterKey = $siteConfig['masterKey'];
        $this->predisClient = $predisClient;
    }

    protected function getRedisClient() {
        return $this->predisClient;
    }

    protected function getMasterKey() {
        return $this->masterKey;
    }
}