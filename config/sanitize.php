<?php
// text santitize
function sanitize_str($data,$msg = "message"){
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    if($data == "" ) return_fail("bad string!",$msg);
    return $data;
}

function sanitize_int($data,$msg = "message"){
    $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
    if($data == "" ) return_fail("bad int!",$msg);
    return $data;
}

function sanitize_float($data,$msg = "message"){
    $data = filter_var($data, FILTER_VALIDATE_FLOAT);
    if($data == "" ) return_fail("bad float",$msg);
    return $data;
}

?>