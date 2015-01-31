<?php

// http://www.sitepoint.com/an-introduction-to-redis-in-php-using-predis/

global $predisClient;
$predisClient = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);


try {
    $predisClient->connect();
} catch (PredisNetworkConnectionException $exception) {
// do stuff to handle the fact that you weren't able to connect to the server
// here I'm using exit() just as a quick example...
    exit("Whoops, couldn't connect to the Redis instance!");
}
