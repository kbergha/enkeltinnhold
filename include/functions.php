<?php

function debug($object, $fullStack = false) {
    echo '<pre>';
    $limit = 1;
    if($fullStack == true) {
        $limit = 0;
    }
    debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit);
    var_dump($object);
    echo '</pre>';
}