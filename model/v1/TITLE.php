<?php
class TITLE{
        public $title;
        public function __construct(){
                $this->title = R::dispense('title');
        }
        public function insert($data){
                // 1. name
                $this->title->name = (string) isset($data['name']) ? sanitize_str($data['name'],"title->insert : name") :  return_fail('title->insert : name is not defined in requested data');

                // 2. currency
                $currency = (int) isset($data['currency']) ? sanitize_int($data['currency'],"title->insert : currency") :  return_fail('title->insert : currency is not defined in requested data');
                $currency = R::load('currency',$currency);
                if($currency->id == 0 ) return_fail('title->insert : currency can not find ');
                $this->title->currency = $currency;

                // 3. calculation
                $this->title->calculation = (string) isset($data['calculation']) ? sanitize_str($data['calculation'],"title->insert : calculation") :  return_fail('title->insert : calculation is not defined in requested data');

                // 4. exchange_rate
                $this->title->exchange_rate = (int) isset($data['exchange_rate']) ? sanitize_int($data['exchange_rate'],"title->insert : exchange_rate") :  1; // this is default 


                // 5. opening_date
                $this->title->opening_date = (string) isset($data['opening_date']) ? sanitize_str($data['opening_date'],"title->insert : opening_date") :  return_fail('title->insert : opening_date is not defined in requested data');
                $opening_date = strtotime($this->title->opening_date); // time to unix
                $this->title->opening_date = date("Y-m-d",$opening_date); // well formated time

                // 6. balance
                $this->title->balance = (int) isset($data['balance']) ? sanitize_int($data['balance'],"title->insert : balance") :  return_fail('title->insert : balance is not defined in requested data');

                // 7. total_income
                $this->title->total_income = (int) isset($data['total_income']) ? sanitize_int($data['total_income'],"title->insert : total_income") :  return_fail('title->insert : total_income is not defined in requested data');

                // 8. total_expense
                $this->title->total_expense = (int) isset($data['total_expense']) ? sanitize_int($data['total_expense'],"title->insert : total_expense") :  return_fail('title->insert : total_expense is not defined in requested data');

                // 9. created_date
                $this->title->created_date = date("Y-m-d h:m:s");

                // 10. modified_date
                $this->title->modified_date = date("Y-m-d h:m:s");

                try{
                        $id = R::store($this->title);

                        // insert appropriate finance
                        $finance = R::dispense('finance');
                        $finance->ops = "opening_balance";
                        $finance->amount = $this->title->balance;
                        $finance->account_balance = null;
                        $finance->title_balance = $this->title->balance;
                        $finance->total_income = $this->title->total_income;
                        $finance->total_expense = $this->title->total_expense;
                        $finance->exchange_rate = $this->title->exchange_rate;
                        $finance->description = "Title : ". $this->title->name . " opening balance is added";
                        $finance->created_date = $this->title->opening_date;
                        $finance->modified_date = $this->title->created_date;
                        $finance->payment_method = "operator";
                        $finance->payment_data = "operator";
                        //$finance->account = null;
                        $finance->title = $this->title; 
                        $finance->auth = null;
                        $finance_id = R::store($finance);

                        $test = $this->title->currency; // to get full data 
                        return_success("title->insert",$this->title);
                }catch(Exception $exp){
                        return_fail("title->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $titles = R::find('title',' id > ? ', [ $last_id ]);
                else $titles = R::find('title', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                $test;
                foreach($titles AS $index=>$title){
                        $test = $title->currency; // to get relate data
                        $return_data[] = $title;
                }
                return_success("title->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('title->update : id is not defined in requested data');
                $title = R::load( 'title', $id );
                if($title->id == 0 ) return_fail("title->update : no data for requested id");

                $title->name = (string) isset($data['name']) ? sanitize_str($data['name'],"title->update : name") :  $title->name;

                $title->currency_id = (int) isset($data['currency']) ? sanitize_int($data['currency'],"title->update : currency") :  $title->currency_id;

                $title->calculation = (string) isset($data['calculation']) ? sanitize_str($data['calculation'],"title->update : calculation") :  $title->calculation;
                
                try{
                        R::store($title);
                        $test = $title->currency;
                        return_success("title->update",$title);
                }catch(Exception $exp){
                        return_fail("title->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('title->delete : id is not defined in requested data');
                $title = R::load( 'title', $id );
                if($title->id == 0 ) return_fail("title->delete : no data for requested id");
                try{
                        R::trash($title);
                        return_success("title->delete",$title);
                }catch(Exception $exp){
                        return_fail("title->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>