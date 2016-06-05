<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 05/06/2016
 * Time: 9:25
 */


function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}