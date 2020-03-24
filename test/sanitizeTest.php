<?php
require('../config/return_function.php');
require('../config/sanitize.php');

/*
    what we check
    1. isset, if not -> return
    2. type error and sanitize, if not appropriate -> return
    3. prepare query , if no effect or resut ->return
    4. return data / status
*/
$data = $_GET;
$name = isset($data['name']) ? sanitize_str($data['name'],"bank->insert : name ") : return_fail('bank->insert : name is not defined in requested data');
echo "name is ".$name." OK";

$intake = isset($data['intake']) ? sanitize_int($data['intake'],"bank->insert : intake ") : return_fail('bank->insert : intake is not defined in requested data');
echo "intake is ".$intake." OK";

$cash = isset($data['cash']) ? sanitize_float($data['cash'],"bank->insert : cash ") : return_fail('bank->insert : cash is not defined in requested data');
echo "cash is ".$cash." OK";
?>