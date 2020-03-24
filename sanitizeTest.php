<?php
require('../config/return_function.php');
require('../config/sanitize.php');

$data = $_GET;
$name = isset($data['name']) ? $data['name'] : return_fail('bank->insert : name is not defined in requested data');

?>