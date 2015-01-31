<?php
/*
 * Beep, beep, initializing boot up sequence, beep. *
 * */

require_once '../config/site-config.php';
require_once '../vendor/autoload.php';
require_once '../vendor/ircmaxell/password-compat/lib/password.php';
require_once 'functions.php';

// Register autoloader. Example from http://www.php-fig.org/psr/psr-4/examples/
/**
 * An example of a project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Enkeltinnhold';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/../src';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});


require_once 'session.php';
require_once 'predis.php';


if(!isset($siteConfig['masterKey']) || mb_strlen($siteConfig['masterKey']) < 3) {
    die('Missing or wrong master key!');
}

/*
 * Resolve current Page
 * */

global $page;
$page = new \Enkeltinnhold\Page();

if(!$page->resolvePage()) {
    // 404
    $page->sendHeaders(404);
} else {
    $page->sendHeaders();
}