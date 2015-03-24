<?php


namespace Enkeltinnhold;

class Page extends Base {
    private $resolved = false;
    protected $loaded = false;
    private $pageKeyPrefix = 'page:';

    // Common page elements
    public $pageKey = null;
    public $title = null;
    public $digest = null;
    public $created = null;
    public $updated = null;
    public $updatedBy = null;
    protected $otherData = null;
    protected $pageData; // @todo: masse greier - dele opp, legge til element for element i admin.

    public function __construct($pageKey = null) {
        parent::__construct();
        $this->pageKey = $pageKey;
    }

    public function setPageData($pageData) {
        $this->pageData = $pageData;
    }

    public function setOtherData($otherData) {
        $this->otherData = $otherData;
    }

    public function save() {
        $predisClient = $this->getPredisClient();

        $allData = array();
        $allData['title'] = $this->title;
        $allData['digest'] = $this->digest;
        $allData['created'] = $this->created;
        $allData['updated'] = $this->updated;
        $allData['updatedBy'] = $this->updatedBy;
        $allData['pageData'] = $this->pageData;
        $allData['otherData'] = $this->otherData;

        $status = $predisClient->hmset($this->getMasterKey().':'.$this->pageKey, $allData);

        if(get_class($status) == 'Predis\Response\Status' && $status->getPayLoad() == 'OK') {
            return true;
        } else {
            return false;
        }
    }

    public function load($all = true) {
        $predisClient = $this->getPredisClient();

        // @todo: Also check if sismember

        $allData = $predisClient->hgetall($this->getMasterKey().':'.$this->pageKey);

        if(is_array($allData) && count($allData)) {
            foreach ($allData as $property => $value) {
                switch($property) {
                    case 'title':
                        $this->title = $value;
                        break;
                    case 'digest':
                        $this->digest = $value;
                        break;
                    case 'created':
                        $this->created = $value;
                        break;
                    case 'updated':
                        $this->updated = $value;
                        break;
                    case 'updatedBy':
                        $this->updatedBy = $value;
                        break;
                    case 'pageData':
                        if($all == true) {
                            $this->pageData = $value;
                        }
                        break;
                    case 'otherData':
                        if($all == true) {
                            $this->otherData = $value;
                        }
                        break;
                    default:
                        // Unknown, ignore and log?
                        break;
                }
            }
            $this->loaded = true;
            return true;
        } else {
            return false;
        }
        unset($allData);
    }

    public function resolvePage() {
        $request = trim(strip_tags($_SERVER['QUERY_STRING']));

        $predisClient = $this->getPredisClient();

        if(mb_strlen($request) == 0) {
            // Index
            $this->pageKey = $this->pageKeyPrefix.'reserved:index';
        } else {
            $request = explode('/', $request);
            if(is_array($request)) {
                $request = explode('=', $request[0]);
                if(isset($request[1])) {
                    $this->pageKey = $this->pageKeyPrefix.$request[1];
                }
            }
        }

        if($this->pageKey == $this->pageKeyPrefix.'reserved:index') {
            // May return zero, check for null
            if($predisClient->zrank($this->getMasterKey().':reservedpages', $this->pageKey) !== null) {
                $this->resolved = true;
                $this->load();
            } else {
                $this->pageKey = 'page:reserved:404';
                $this->load();
            }
        } else {
            // May return zero, check for null
            if($predisClient->zrank($this->getMasterKey().':allpages', $this->pageKey) !== null) {
                $this->resolved = true;
                $this->load();
            } else {
                $this->pageKey = 'page:reserved:404';
                $this->load();
            }
        }
        return $this->resolved;
    }

    public function getPageData() {
        return $this->pageData; // @todo: masse greier - dele opp, legge til element for element i admin.
    }

    public function getOtherData() {
        return $this->otherData; // @todo: masse greier - dele opp, legge til element for element i admin.
    }

    public function isResolved() {
        return $this->resolved;
    }

    public function isLoaded() {
        return $this->loaded;
    }

    public function isActive() {
        $activePage = $this->getPage();
        if($activePage->pageKey == $this->pageKey) {
            return true;
        }
        return false;
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

    public function getAllPageKeys($offset = 0, $limit = 5) {

        // @todo: order by, fix limit
        $predisClient = $this->getPredisClient();
        return $predisClient->zrevrange($this->getMasterKey().':allpages', $offset, $limit);

    }

    public function getAllReservedPageKeys($offset = 0, $limit = 10) {

        // @todo: order by, fix limit

        $predisClient = $this->getPredisClient();
        return $predisClient->zrevrange($this->getMasterKey().':reservedpages', $offset, $limit);
    }

    public function getURL() {
        $parts = (explode('page:', $this->pageKey));
        if(isset($parts[1])) {
            return '/'.$parts[1];
        }
    }


}