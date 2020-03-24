<?php
require '../lib/rb.php';
//R::setup(); # setup sqlite db
R::setup( 'mysql:host=localhost;dbname=finance', 'root', '' ); # real db
// $post = R::dispense( 'post' ); # table / record
// $post->title = 'My holiday';  # column 
// $id = R::store( $post ); # return inserted id
// $post = R::load( 'post', $id ); # get specific id post
// echo $post->title; // as membership assess operator
// echo $post['title']; // as array
// R::trash($post); # delete record (bean)

// List our model
$currency = R::dispense('currency');
$bank = R::dispense('bank');
$account = R::dispense('account');
$finalcalculation = R::dispense('finalcalculation');
$accounthead = R::dispense('accounthead');
$casetype = R::dispense('casetype');
$authperson = R::dispense('authperson');
$paymentmethod = R::dispense('paymentmethod');
$case = R::dispense('case');

$currency->name = "MMK";
$mmk_id = R::store($currency);
R::trash($currency);

$bank->name = "MWD BANK";
$mwd_id = R::store($bank);
R::trash($bank);

$account->name = "01 Account Name";
$account->currency = $currency;
$account->bank = $bank;
$account_id = R::store($account);
R::trash($account);

$finalcalculation->name = "balance";
$finalcalculation_id = R::store($finalcalculation);
R::trash($finalcalculation);

$accounthead->name = "01 Account Head ";
$accounthead->account = $account;
$accounthead->finalcalculation = $finalcalculation;
$accounthead_id = R::store($accounthead);
R::trash($accounthead);

$casetype->name = "income";
$casetype_id = R::store($casetype);
R::trash($casetype);

$authperson->name = "Director";
$authperson_id = R::store($authperson);
R::trash($authperson);

$paymentmethod->name = "Cheque";
$paymentmethod_id = R::store($paymentmethod);
R::trash($paymentmethod);


$case->currency = $currency;
$case->account = $account;
$case->accounthead = $accounthead;
$case->casetype = $casetype;
$case->amount = 1200000;
$case->description = "This is description";
$case->date = date("Y/m/d");
$case->authperson = $authperson;
$case->paymentmethod = $paymentmethod;
$case->paymentdata = "cheque no : MWD 34324 23 4234 242 to Manager ";
$case_id = R::store($case);
R::trash($case);


?>