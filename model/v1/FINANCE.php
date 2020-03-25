<?php
class FINANCE{
        public $finance;
        public function __construct(){
                $this->finance = R::dispense('finance');
        }
        public function insert($data){
                // 1. Account
                $account_id = (int) isset($data['account']) ? sanitize_int($data['account'],"finance->insert : account") :  return_fail('finance->insert : account is not defined in requested data');
                $account = R::load('account',$account_id);
                if($account->id == 0 ) return_fail('finance->insert : account can not find ');
                $this->finance->account = $account;

                // 2. Title
                $title = (int) isset($data['title']) ? sanitize_int($data['title'],"finance->insert : title") :  return_fail('finance->insert : title is not defined in requested data');
                $title = R::load('title',$title);
                if($title->id == 0 ) return_fail('finance->insert : title can not find ');
                $this->finance->title = $title;

                // 3. ops
                $this->finance->ops = (string) isset($data['ops']) ? sanitize_str($data['ops'],"finance->insert : ops") :  return_fail('finance->insert : ops is not defined in requested data');

                // 4. amount
                $this->finance->amount = (int) isset($data['amount']) ? sanitize_int($data['amount'],"finance->insert : amount") :  return_fail('finance->insert : amount is not defined in requested data');
                
                // 5. exchange rate
                $this->finance->exchange_rate = (int) isset($data['exchange_rate']) ? sanitize_int($data['exchange_rate'],"finance->insert : exchange_rate") :  0;
                
                // 6. description
                $this->finance->description = (string) isset($data['description']) ? sanitize_str($data['description'],"finance->insert : description") :  return_fail('finance->insert : description is not defined in requested data');

                // 7. created_date
                $this->finance->created_date = (string) isset($data['created_date']) ? sanitize_str($data['created_date'],"finance->insert : created_date") :  return_fail('finance->insert : created_date is not defined in requested data');
                $created_date = strtotime($this->finance->created_date); // time to unix
                $this->finance->created_date = date("Y-m-d",$created_date); // well formated time
                
                // 8. modified_date
                $this->finance->modified_date = date("Y-m-d H:m:s");
                
                // 9. Auth Person
                $auth = (int) isset($data['auth']) ? sanitize_int($data['auth'],"finance->insert : auth") :  return_fail('finance->insert : auth is not defined in requested data');
                $auth = R::load('auth',$auth);
                if($auth->id == 0 ) return_fail('finance->insert : auth can not find ');
                $this->finance->auth = $auth;

                // 10. payment method
                $this->finance->payment_method = (string) isset($data['payment_method']) ? sanitize_str($data['payment_method'],"finance->insert : payment_method") :  return_fail('finance->insert : payment_method is not defined in requested data');

                // 11. paymentdata
                $this->finance->payment_data = (string) isset($data['payment_data']) ? sanitize_str($data['payment_data'],"finance->insert : payment_data") :  return_fail('finance->insert : payment_data is not defined in requested data');

                // 12. account balance
                /*
                        1. get account  if the account is defined
                        2. calculate account' balance according to ops and amount 
                        3. update thet account
                        4. set the account_account balance
                */
                if($this->finance->ops == "income") {
                        $this->finance->account_balance = $this->finance->account->balance + $this->finance->amount;$this->finance->title_balance = $this->finance->title->balance + $this->finance->amount;
                        if($this->finance->title->calculation == "balance"){
                                $this->finance->total_income = $this->finance->title->total_income + $this->finance->amount;
                                $this->finance->total_expense = $this->finance->title->total_expense;
                        }
                        else{
                                $this->finance->total_income = 0 ;
                                $this->finance->total_expense = 0 ;
                        }
                }
                if($this->finance->ops == "expense") {
                        $this->finance->account_balance = $this->finance->account->balance - $this->finance->amount;
                        $this->finance->title_balance = $this->finance->title->balance - $this->finance->amount;
                        if($this->finance->title->calculation == "balance"){
                                $this->finance->total_expense = $this->finance->title->total_expense + $this->finance->amount;
                                $this->finance->total_income = $this->finance->title->total_income;
                        }
                        else{
                                $this->finance->total_expense = 0 ;
                                $this->finance->total_income = 0 ;
                        }
                        
                }
                
                // update external redbean :D
                // opening_date, balance, exchange_rate , modified_date
                $account->balance = $this->finance->account_balance;
                $account->opening_date = $this->finance->created_date;
                $account->exchange_rate = $this->finance->exchange_rate;
                $account->modified_date = $this->finance->modified_date;


                // 13. title balance
                // exchange_rate, opening_date, balance, total_income, total_expense, modified_date
                $title->balance = $this->finance->title_balance;
                $title->opening_date = $this->finance->created_date;
                $title->total_income = $this->finance->total_income; // may be zero or update
                $title->total_expense = $this->finance->total_expense; // may be zero or update
                $title->exchange_rate = $this->finance->exchange_rate;
                $title->modified_date = $this->finance->modified_date;



                //echo "payment_data is ".$this->finance->payment_data;

                // 14. From : Name
                $this->finance->from_name = (string) isset($data['from_name']) ? sanitize_str($data['from_name'],"finance->insert : from_name") :  return_fail('finance->insert : from_name is not defined in requested data');

                // 14. From : Company
                $this->finance->from_company = (string) isset($data['from_company']) ? sanitize_str($data['from_company'],"finance->insert : from_company") :  return_fail('finance->insert : from_company is not defined in requested data');

                // 15. From : Address
                $this->finance->from_address = (string) isset($data['from_address']) ? sanitize_str($data['from_address'],"finance->insert : from_address") :  return_fail('finance->insert : from_address is not defined in requested data');

                // 17. From : Phone
                $this->finance->from_phone = (string) isset($data['from_phone']) ? sanitize_str($data['from_phone'],"finance->insert : from_phone") :  return_fail('finance->insert : from_phone is not defined in requested data');

                // 18. To : Name
                $this->finance->to_name = (string) isset($data['to_name']) ? sanitize_str($data['to_name'],"finance->insert : to_name") :  return_fail('finance->insert : to_name is not defined in requested data');

                // 19. To : Company
                $this->finance->to_company = (string) isset($data['to_company']) ? sanitize_str($data['to_company'],"finance->insert : to_company") :  return_fail('finance->insert : to_company is not defined in requested data');

                // 20. To : Address
                $this->finance->to_address = (string) isset($data['to_address']) ? sanitize_str($data['to_address'],"finance->insert : to_address") :  return_fail('finance->insert : to_address is not defined in requested data');

                // 21. To : Phone
                $this->finance->to_phone = (string) isset($data['to_phone']) ? sanitize_str($data['to_phone'],"finance->insert : to_phone") :  return_fail('finance->insert : to_phone is not defined in requested data');
                
                R::begin(); // start the transaction 
                try{
                        $id = R::store($this->finance); // query 1 : insert finance
                        R::store($account); // query 2 : update account
                        R::store($title); // query 3 : update title
                        R::commit(); // execte the actual query set

                        $test = $this->finance->account; // to get relate data
                        $test = $this->finance->account->bank; // to get relate data
                        $test = $this->finance->title; // to get relate data
                        $test = $this->finance->title->bank; // to get relate data
                        $test = $this->finance->title->currency; // to get relate data
                        $test = $this->finance->auth; // to get relate data
                        return_success("finance->insert",$this->finance);
                }catch(Exception $exp){
                        R::rollback(); // something wentfinance->insert : exception wrong 
                        return_fail(" ( rollback ) ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                $income = "income";
                $expense = "expense";
                if($limit == 0 ) $finances = R::find('finance',' id > ?  AND ( ops = ? OR ops = ? )', [ $last_id, $income, $expense]);
                else $finances = R::find('finance', ' id > ? AND (ops = ? OR ops = ? )   LIMIT ?', [ $last_id,$income,$expense, $limit ] );
                $return_data = array();
                $test;
                foreach($finances AS $index=>$finance){
                        //echo "<br>finance id to check : ".$finance->id;
                        $test = $finance->account; // to get relate data
                        //$test = $finance->account->bank;
                        $test = $finance->title; // to get relate data
                        //$test = $finance->title->bank;
                        $test = $finance->title->currency;
                        $test = $finance->auth; // to get relate data
                        $return_data[] = $finance;
                }
                return_success("finance->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('finance->update : id is not defined in requested data');
                $finance = R::load( 'finance', $id );
                if($finance->id == 0 ) return_fail("finance->update : no data for requested id");

                $finance->account_id = (int) isset($data['account']) ? sanitize_int($data['account'],"finance->update : title") :  $finance->account_id;

                $finance->title_id = (int) isset($data['title']) ? sanitize_int($data['title'],"finance->update : title") :  $finance->title_id;

                $finance->ops = (string) isset($data['ops']) ? sanitize_str($data['ops'],"finance->update : ops") :  $finance->ops;

                $finance->amount = (int) isset($data['amount']) ? sanitize_int($data['amount'],"finance->update : amount") :  $finance->amount;

                $finance->exchange_rate = (int) isset($data['exchange_rate']) ? sanitize_int($data['exchange_rate'],"finance->update : exchange_rate") :  $finance->exchange_rate;

                $finance->description = (string) isset($data['description']) ? sanitize_str($data['description'],"finance->update : description") :  $finance->description;

                $finance->payment_method = (string) isset($data['payment_method']) ? sanitize_str($data['payment_method'],"finance->update : payment_method") :  $finance->payment_method;

                $finance->payment_data = (string) isset($data['payment_data']) ? sanitize_str($data['payment_data'],"finance->update : payment_data") :  $finance->payment_data;

                $finance->created_date = (string) isset($data['created_date']) ? sanitize_str($data['created_date'],"finance->update : created_date") :  $finance->created_date;
                $created_date = strtotime($finance->created_date); // time to unix
                $finance->created_date = date("Y-m-d",$created_date); // well formated time

                // modified_date
                $finance->modified_date = date("Y-m-d H:m:s");

                // auth person
                $finance->auth_id = (int) isset($data['auth']) ? sanitize_int($data['auth'],"finance->update : auth") :  $finance->auth_id;
                try{
                        R::store($finance);
                        $test = $finance->account; // to get relate data
                        $test = $finance->account->bank;
                        $test = $finance->title; // to get relate data
                        $test = $finance->title->bank;
                        $test = $finance->title->currency;
                        $test = $finance->auth; // to get relate data
                        return_success("finance->update",$finance);
                }catch(Exception $exp){
                        return_fail("finance->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('finance->delete : id is not defined in requested data');
                $finance = R::load( 'finance', $id );
                if($finance->id == 0 ) return_fail("finance->delete : no data for requested id");
                try{
                        R::trash($finance);
                        return_success("finance->delete",$finance);
                }catch(Exception $exp){
                        return_fail("finance->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>