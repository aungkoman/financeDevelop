<?php
class ACCOUNT{
        public $account;
        public function __construct(){
                $this->account = R::dispense('account');
        }
        public function insert($data){
                // 1. name
                $this->account->name = (string) isset($data['name']) ? sanitize_str($data['name'],"account->insert : name") :  return_fail('account->insert : name is not defined in requested data');

                // 2. currency
                $currency = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account->insert : currency") :  return_fail('account->insert : currency is not defined in requested data');
                $currency = R::load('currency',$currency);
                if($currency->id == 0 ) return_fail('account->insert : currency can not find ');
                $this->account->currency = $currency;

                // 3. bank
                $bank = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account->insert : bank") :  return_fail('account->insert : bank is not defined in requested data');
                $bank = R::load('bank',$bank);
                if($bank->id == 0 ) return_fail('account->insert : bank can not find ');
                $this->account->bank = $bank;

                // created date
                // modified date
                // opening date
                // balance 
                /*
                        These fields are added in 2020-03-18 10:42
                */

                // 4. opening_date
                $this->account->opening_date = (string) isset($data['opening_date']) ? sanitize_str($data['opening_date'],"account->insert : opening_date") :  return_fail('account->insert : opening_date is not defined in requested data');
                $opening_date = strtotime($this->account->opening_date); // time to unix
                $this->account->opening_date = date("Y-m-d",$opening_date); // well formated time

                // 5. balance
                $this->account->balance = (int) isset($data['balance']) ? sanitize_int($data['balance'],"account->insert : balance") :  return_fail('account->insert : balance is not defined in requested data');

                // 6. exchange_rate
                $this->account->exchange_rate = (int) isset($data['exchange_rate']) ? sanitize_int($data['exchange_rate'],"account->insert : exchange_rate") :  1; // default is ONE ( may be MMK :D )

                // 6. created_date
                $this->account->created_date = date("Y-m-d h:m:s");

                // 7. modified_date
                $this->account->modified_date = date("Y-m-d h:m:s");

                try{
                        $id = R::store($this->account);
                        //$test = $this->account->currency;
                        //echo "test currency is ".$test;
                        //$test = $this->account->bank;


                        $finance = R::dispense('finance');
                        $finance->ops = "opening_balance";
                        $finance->amount = $this->account->balance;
                        $finance->account_balance = $this->account->balance;
                        $finance->title_balance = null;
                        $finance->exchange_rate = $this->account->exchange_rate;
                        $finance->description = "Account ".$this->account->name . " opening balance is added";
                        $finance->created_date = $this->account->opening_date;
                        $finance->modified_date = $this->account->created_date;
                        $finance->payment_method = "operator";
                        $finance->payment_data = "operator";
                        $finance->account = $this->account;
                        //$finance->title = null;
                        $finance->auth = null;

                        $finance_id = R::store($finance); // insert

                        $test = $this->account->currency;
                        $test = $this->account->bank;

                        return_success("account->insert",$this->account);
                }catch(Exception $exp){
                        return_fail("account->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $accounts = R::find('account',' id > ? ', [ $last_id ]);
                else $accounts = R::find('account', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                $test;
                foreach($accounts AS $index=>$account){
                        $test = $account->bank; // to get related foreign data
                        $test = $account->currency; // to get relate data
                        $return_data[] = $account;
                }
                return_success("account->select ".count($return_data),$return_data);
        }
        public function update($data){
                // 1. id
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('account->update : id is not defined in requested data');
                $account = R::load( 'account', $id );
                if($account->id == 0 ) return_fail("account->update : no data for requested id");

                // 2. name
                $account->name = (string) isset($data['name']) ? sanitize_str($data['name'],"account->update : name") :  $account->name;

                // 3. currency
                $currency_id = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account->update : currency") :  $account->currency_id;
                $currency = R::load('currency',$currency_id);
                if($currency->id == 0 ) return_fail('account->update : currency can not find '. $currency_id);
                $account->currency = $currency;

                //$account->currency_id = (int) isset($data['currency']) ? sanitize_int($data['currency'],"account->update : currency") :  $account->currency_id;

                // 4. bank
                $bank_id = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account->update : bank") :  $account->bank_id;
                $bank = R::load('bank',$bank_id);
                if($bank->id == 0 ) return_fail('account->update : bank can not find ' . $bank_id);
                $account->bank = $bank;
                //$account->bank_id = (int) isset($data['bank']) ? sanitize_int($data['bank'],"account->update : bank") :  $account->bank_id;

                // 5. modified_date
                $account->modified_date = date("Y-m-d h:m:s");

                // 6. opening_date
                $account->opening_date = (string) isset($data['opening_date']) ? sanitize_str($data['opening_date'],"account->update : opening_date") :  $account->opening_date;
                $opening_date = strtotime($account->opening_date); // time to unix
                $account->opening_date = date("Y-m-d",$opening_date); // well formated time

                // 7. balance
                $account->balance = (int) isset($data['balance']) ? sanitize_int($data['balance'],"account->update : balance") :  $account->balance;

                // 8. we just omit created_date :D

                try{
                        R::store($account);
                        $test = $account->currency;
                        $test = $account->bank;
                        return_success("account->update",$account);
                }catch(Exception $exp){
                        return_fail("account->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('account->delete : id is not defined in requested data');
                $account = R::load( 'account', $id );
                if($account->id == 0 ) return_fail("account->delete : no data for requested id");
                try{
                        R::trash($account);
                        return_success("account->delete",$account);
                }catch(Exception $exp){
                        return_fail("account->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>