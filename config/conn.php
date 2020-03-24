<?php
$servername = $_SERVER['HTTP_DB_HOST'];
$username = $_SERVER['HTTP_DB_USERNAME'];
$password = $_SERVER['HTTP_DB_PASSWORD'];
$db_name = $_SERVER['HTTP_DB_DBNAME'];
R::setup( 'mysql:host='.$servername.';dbname='.$db_name.'', $username, $password ); # real db
//R::freeze( TRUE ); // not to change db schema in runtime
// try{
	
// }catch(Exception $e){
// 	echo $e;
// }

// $date = isset($_GET['date']) ? $_GET['date'] : '2020-03-05';
// try{
// 	$cases = R::find('case', ' date = ? ', [ $date ] ); # find method
// }catch(Exception $e){
// 	echo "query exception ".$e;
// }
// echo "<p>".$date."</p>";
?>
