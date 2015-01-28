<?php

session_start();
// 20% chance of ID-regen.
$rand = rand(0,4);
if($rand = 0) {
    session_regenerate_id(true);
}