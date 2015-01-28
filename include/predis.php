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
    exit("whoops, couldn't connect to the redis instance!");
}

/*
echo "<pre>\n";
//var_dump($client);

$client->incr("en-fin-n0kkel");
var_dump($client->get('en-fin-n0kkel'));


$client->hmset("testArray", array(
        "brand" => "Toyota",
        "model" => "Yaris",
        "license number" => "RO-01-PHP",
        "year of fabrication" => 2010,
        "nr_stats" => 0)
);
$client->hset("testArray", "year of fabrication", date("Y"));

var_dump($client->hgetall('testArray'));

echo "</pre>\n";
*/
