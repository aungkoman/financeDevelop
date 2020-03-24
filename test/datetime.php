<?php
$date = isset($_GET['date']) ? $_GET['date'] : '2020-03-05';
echo "date is ".$date;
$phpdate = strtotime($date);
echo "<br> php date is ".$phpdate;
$inputdate = date("Y-m-d",$phpdate);
echo "<br> input date is ".$inputdate;

$defaultdate = date($date);
echo "<br> how default date is ".$defaultdate;
?>