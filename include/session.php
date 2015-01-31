<?php
session_start();
// 10% chance of ID-regen.
$rand = rand(0,9);
if($rand = 0) {
    session_regenerate_id(true);
}