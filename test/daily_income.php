<!DOCTYPE html>
<?php
require '../lib/rb.php';
R::setup( 'mysql:host=localhost;dbname=finance', 'root', '' ); # real db
?>

<html>
<head>
<style>
table {
  border-collapse: collapse;
}

table, td, th {
  border: 1px solid black;
}
</style>
</head>
<body>
    <h3>Query Form</h3>
    <form action="daily_income.php" method="get">
        <label for="date">Date</label><br>
        <input type="date" name="date" id="date"><br><br>
        <label for="currency">Currency</label><br>
        <select name="currency" id="currency">
            <?php
                $currencys = R::find('currency', '', [] ); # find method
                foreach($currencys AS $index => $currency){
                    //echo "<option value='".$case->id."'>".$case->name."</option>";
                    echo "<option value='".$currency->id."'>".$currency->name."</option>";
                }
            ?>
        </select> <br><br>
        <input type="submit" value="query">
    </form>

<?php
$date = isset($_GET['date']) ? $_GET['date'] : '2020-03-05';
$cases = R::find('case', ' date = ? ', [ $date ] ); # find method
echo "<p>".$date."</p>";
// /echo "case count for date ".$date." is ".count($cases);
//print_r($cases);
// foreach($cases AS $index => $case){
//     echo "<br>".$case->description." and ".$case->currency->name;
// }
// for($i = 0 ; $i < count($cases); $i++){
//     echo "<br>".$cases[$i]->description;
// }
/*
    what we have to do:
    get date
    find income case for the day, total income
    find expense case for the day, total expense
*/
?>

<h3>Income</h3>
<table class="table">
    <thead>
        <th>Serial No</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Account</th>
        <th>Account Head</th>
        <th>Auth Person</th>
        <th>Payment Method</th>
        <th>Payment Data</th>
        <th>Date</th>
    </thead>
    <tbody>

<?php
// 
$casetype = 4; // income
$serialno = 1 ;
$totalincome = 0 ;
$cases = R::find('case', ' date = ? AND casetype_id = ?', [ $date, $casetype ] ); # find method
//echo "Case type and date   ".$casetype." : ".$date." is ".count($cases);
foreach($cases AS $index => $case){
    echo "<tr>".
            "<td>".$serialno."</td>".
            "<td>".$case->description."</td>".
            "<td>".$case->amount."</td>".
            "<td>".$case->account->name."</td>".
            "<td>".$case->accounthead->name."</td>".
            "<td>".$case->authperson->name."</td>".
            "<td>".$case->paymentmethod->name."</td>".
            "<td>".$case->paymentdata."</td>".
            "<td>".$case->date."</td>".
        "</tr>";
    $serialno++;
    $totalincome += $case->amount;
}
echo "<tr>".
            "<td></td>".
            "<td>Total Income</td>".
            "<td>".$totalincome."</td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
        "</tr>";
?>
    </tbody>
</table>





<h3>Expense</h3>
<table class="table">
    <thead>
        <th>Serial No</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Account</th>
        <th>Account Head</th>
        <th>Auth Person</th>
        <th>Payment Method</th>
        <th>Payment Data</th>
        <th>Date</th>
    </thead>
    <tbody>

<?php
// 
$casetype = 5; // expense
$serialno = 1 ;
$totalexpense = 0 ;
$cases = R::find('case', ' date = ? AND casetype_id = ?', [ $date, $casetype ] ); # find method
//echo "Case type and date   ".$casetype." : ".$date." is ".count($cases);
foreach($cases AS $index => $case){
    echo "<tr>".
        "<td>".$serialno."</td>".
        "<td>".$case->description."</td>".
        "<td>".$case->amount."</td>".
        "<td>".$case->account->name."</td>".
        "<td>".$case->accounthead->name."</td>".
        "<td>".$case->authperson->name."</td>".
        "<td>".$case->paymentmethod->name."</td>".
        "<td>".$case->paymentdata."</td>".
        "<td>".$case->date."</td>".
        "</tr>";
    $serialno++;
    $totalexpense += $case->amount;
}
echo "<tr>".
            "<td></td>".
            "<td>Total Expense</td>".
            "<td>".$totalexpense."</td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
            "<td></td>".
        "</tr>";
?>
    </tbody>
</table>


</body>
</html>