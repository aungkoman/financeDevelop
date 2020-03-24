<?php
$date_data = isset($_GET['date']) ? $_GET['date'] : '1 December, 2019';
//echo strtotime("10 September 2000"), "\n";
$date_data2 = '1 December 2020';
echo $date_data . " to unix timestamp ".strtotime($date_data). '<br>';
echo $date_data2 . " to unix timestamp ".strtotime($date_data2). '<br>';

echo strtotime($date_data) . " to date time format ".date("Y-m-d",strtotime($date_data)). '<br>';
echo strtotime($date_data2) . " to date time format ".date("Y-m-d",strtotime($date_data2)). '<br>';

?>