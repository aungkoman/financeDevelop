<?php
class CALCULATION{
        public $calculation;
        public function __construct(){
                $this->calculation = R::dispense('calculation');
        }
        public function trail($data){
                /*
                        Data
                        1. start date
                        2. end date

                        Data Processing
                        1.  select opening balance for each account at start date
                        2.  select closig balance for each account at end date

                */
                /*
                        ဘာတွေလုပ်ရမလဲ
                        ၁။ စရက် နဲံ ဆုံးရက် ကို ရက်စွဲပုံစံ စစ်ဆေး ပြောင်းယူပါ
                        ၂။ ခေါင်းစဉ် တစ်ခုချင်းစီအလိုက် စုစုပေါင်း သုံးစွဲမှု
                        ၃။ ခေါင်းစဉ် တစ်ခုချင်းစီအလိုက် စုစုပေါင်း ဝင်ငွေ
                        ၄။ စာရင်း တစ်ခုချင်းစီအလိုက် စုစုပေါင်း သုံးစွဲမှု
                        ၅။ စာရင်း တစ်ခုချင်းစီအလိုက် စုစုပေါင်း ဝင်ငွေ
                        ၆။ စာရင်း တစ်ခုချင်းစီအလိုက် စာရင်းဖွင့် လက်ကျန်
                        ၇။ စာရင်း တစ်ခုချင်းစီအလိုက် စာရင်းပိတ် လက်ကျန်


                */
                //  1. start date
                $start_date = (string) isset($data['start_date']) ? sanitize_str($data['start_date'],"calculation->trail : start_date") :  return_fail('calculation->trail : start_date is not defined in requested data'); // do we need to reformat as date , yes We did IT.
                //echo "start date is ".$start_date;
                //echo "start date original is ".$data['start_date'];

                $start_date = strtotime($start_date); // time to unix
                //echo "unix time ".$start_date;
                $start_date = date("Y-m-d",$start_date); // well formated time
                //echo "db date format ".$start_date;


                //  2. end date
                $end_date = (string) isset($data['end_date']) ? sanitize_str($data['end_date'],"calculation->trail : end_date") :  return_fail('calculation->trail : end_date is not defined in requested data'); // do we need to reformat as date
                
                $end_date = strtotime($end_date); // time to unix
                $end_date = date("Y-m-d",$end_date); // well formated time

                // 1. income list for given interval
                $ops = "income";
                $incomeList = R::find('finance','ops = ? AND created_date >= ? AND created_date <= ?',[$ops,$start_date,$end_date]);
                foreach($incomeList AS $index=>$finance){
                        $test = $finance->account;
                        $test = $finance->account->currency;
                        $test = $finance->account->bank;
                        $test = $finance->title;
                        $test = $finance->title->currency;
                }

                // 2. expense list for given interval
                $ops = "expense";
                $expenseList = R::find('finance','ops = ? AND created_date >= ? AND created_date <= ?',[$ops,$start_date,$end_date]);
                foreach($incomeList AS $index=>$finance){
                        $test = $finance->account;
                        $test = $finance->account->currency;
                        $test = $finance->account->bank;
                        $test = $finance->title;
                        $test = $finance->title->currency;
                }

                // 3. sum(income) for each TITLE for given interval
                $incomeDataTitle = R::getAll(
                        'SELECT title_id,SUM(amount) AS total_income FROM finance WHERE ops = :ops AND created_date >= :start_date AND created_date <= :end_date GROUP BY title_id',
                        array(
                                ':ops'=>'income',
                                ':start_date'=>$start_date,
                                ':end_date'=>$end_date
                        )
                );

                // 4. sum(expense) for each TITLE for given interval
                $expenseDataTitle = R::getAll(
                        'SELECT title_id,SUM(amount) AS total_expense FROM finance WHERE ops = :ops AND created_date >= :start_date AND created_date <= :end_date GROUP BY title_id',
                        array(
                                ':ops'=>'expense',
                                ':start_date'=>$start_date,
                                ':end_date'=>$end_date
                        )
                );

                // 5. sum(income) for each ACCOUNT for given interval
                $incomeDataAccount = R::getAll(
                        'SELECT account_id,SUM(amount) AS total_income FROM finance WHERE ops = :ops AND created_date >= :start_date AND created_date <= :end_date GROUP BY account_id',
                        array(
                                ':ops'=>'income',
                                ':start_date'=>$start_date,
                                ':end_date'=>$end_date
                        )
                );

                // 6. sum(expense) for each ACCOUNT for given interval
                $expenseDataAccount = R::getAll(
                        'SELECT account_id,SUM(amount) AS total_expense FROM finance WHERE ops = :ops AND created_date >= :start_date AND created_date <= :end_date GROUP BY account_id',
                        array(
                                ':ops'=>'expense',
                                ':start_date'=>$start_date,
                                ':end_date'=>$end_date
                        )
                );

                // to calculate closing balance for each account
                $calculateData = R::getAll(
                        'SELECT id,account_id,amount,ops,account_balance,created_date FROM finance where account_id IS NOT NULL AND created_date >= :start_date ORDER BY id DESC',
                        array(
                                ':start_date'=>$start_date
                        )
                );
                

                /*
                        ပေးထားတဲ့ အချိန် အတိုင်းအတာ အတွင်းမှာ
                        အဝင် အထွက် ရှိတဲ့ ငွေစာရင်းအားလုံးရဲ့ စာရင်းပိတ် လက်ကျန်
                */
                $closingBalanceAccount = array(); // account_id=>balance
                /*
                        ပေးထားတဲ့ အချိန် အတိုင်းအတာ အတွင်းမှာ
                        စာရင်းဖွင့် လက်ကျန်

                        စာရင်းဖွင့် လက်ကျန် = စာရင်းပိတ် လက်ကျန် + စုစုပေါင်း သုံးစွဲငွေ - စုစုပေါင်း ဝင်ငွေ

                        100

                        +20
                        -30 

                        90

                        100 = 90 + 30 + ( 20 )
                */
                $openingBalanceAccount = array(); // account_id=>balance
                /*
                        မှတ်သားရန်
                          စာရင်းပိတ် လက်ကျန်ဟာ အမြဲတမ်း စာရင်းဖွင့် လက်ကျန်ထက် account အရေအတွက် ပိုများနေနိုင်မလား
                          တူရင် တူမယ်
                          လျော့ရင် လျော့မယ် 
                          ပိုမှာတော့ မဟုတ်

                        ဉပမာ ။
                          စာရင်းပိတ် လက်ကျန်တွက်လိုက်တာ account ဆယ်ခု ပေါ်လာရင်
                          စာရင်းဖွင့်က ဆယ်ခုထက် မပိုနိုင် 

                */
                for($i = 0 ; $i < count($calculateData); $i++){
                        $found = "no";
                        foreach($closingBalanceAccount AS $account_id=>$balance){
                                if($calculateData[$i]['account_id'] == $account_id) $found="yes";
                        }
                        if($found == "no") $closingBalanceAccount[$calculateData[$i]['account_id']] = $calculateData[$i]['account_balance'];
                }
                
                foreach($closingBalanceAccount AS $account_id=>$balance){
                        $expenseFound = "no";
                        $expenseFindex = 0;
                        $incomeFound = "no";
                        $incomeFindex = 0 ;
                        
                        for($i = 0 ; $i < count($expenseDataAccount); $i++){
                                //echo "closingBalanceAccount[ account_id ] : expenseDataAccount[ i ] => ".$account_id ." : ".$expenseDataAccount[$i]['account_id'];

                                if($expenseDataAccount[$i]['account_id'] == $account_id){
                                       $expenseFound = "yes";
                                       $expenseFindex = $i;
                                }
                        }
                        for($i = 0 ; $i < count($incomeDataAccount); $i++){
                                //echo "closingBalanceAccount[ account_id ] : expenseDataAccount[ i ] => ".$account_id ." : ".$expenseDataAccount[$i]['account_id'];

                                if($incomeDataAccount[$i]['account_id'] == $account_id){
                                       $incomeFound = "yes";
                                       $incomeFindex = $i;
                                }
                        }
                        if($expenseFound == "yes"){
                                //echo "it's yes";
                                //echo "totoal expense is ".$expenseDataAccount[$fIndex]['total_expense'];
                                //$openingBalanceAccount[$account_id] = $balance + $expenseDataAccount[$expenseFindex]['total_expense'];
                                $balance += $expenseDataAccount[$expenseFindex]['total_expense'];
                        }
                        if($incomeFound == "yes"){
                                //echo "it's yes";
                                //echo "totoal expense is ".$expenseDataAccount[$fIndex]['total_expense'];
                                $balance -= $incomeDataAccount[$incomeFindex]['total_income'];
                        }
                        $openingBalanceAccount[$account_id] = $balance;
                        // else{
                        //         //echo "it's no";
                        //         $openingBalanceAccount[$account_id] = $balance;
                        // }
                };


                $return_data = array(
                        "closingBalanceAccount"=>$closingBalanceAccount,
                        "openingBalanceAccount"=>$openingBalanceAccount,
                        "incomeList"=>$incomeList,
                        "expenseList"=>$expenseList,
                        "incomeDataTitle"=>$incomeDataTitle,
                        "expenseDataTitle"=>$expenseDataTitle,
                        "incomeDataAccount"=>$incomeDataAccount,
                        "expenseDataAccount"=>$expenseDataAccount,
                        "calcualteData"=>$calculateData
                );
                return_success('calculation->trail from '.$start_date.' to '.$end_date,$return_data);

                

                

                //         'SELECT title_id,amount,created_date FROM finance WHERE ops = :ops AND created_date >= :start_date AND created_date <= :end_date',
                //         array(
                //                 ':ops'=>'income',
                //                 ':start_date'=>$start_date,
                //                 ':end_date'=>$end_date
                //         )
                // );

                // $incomeList = R::getAll(
                //         'SELECT title_id,amount,created_date FROM finance WHERE ops = :ops AND created_date >= :start_date AND created_date <= :end_date',
                //         array(
                //                 ':ops'=>'income',
                //                 ':start_date'=>$start_date,
                //                 ':end_date'=>$end_date
                //         )
                // );


                // $titleIncome = R::getAll('SELECT title_id,SUM(amount) AS total_income FROM finance WHERE ops = :ops GROUP BY title_id', 
                //         array(
                //                 ':ops'=>'income'
                //         )
                // );

                // $expenseList = R::getAll('SELECT title_id,amount FROM finance WHERE ops = :ops ',
                //         array(
                //                 ':ops'=>'expense'
                //         )
                // );
                // $titleExpense = R::getAll('SELECT title_id,SUM(amount) AS total_income FROM finance WHERE ops = :ops GROUP BY title_id', 
                //         array(
                //                 ':ops'=>'expense'
                //         )
                // );

                //$return_data = array($incomeList,$titleIncome,$expenseList,$titleExpense);


        }
        public function profit_and_lose($data){
                
                //  1. start date
                $start_date = (string) isset($data['start_date']) ? sanitize_str($data['start_date'],"calculation->trail : start_date") :  return_fail('calculation->trail : start_date is not defined in requested data');
                $start_date = strtotime($start_date); // time to unix
                $start_date = date("Y-m-d",$start_date); // well formated time

                //  2. end date
                $end_date = (string) isset($data['end_date']) ? sanitize_str($data['end_date'],"calculation->trail : end_date") :  return_fail('calculation->trail : end_date is not defined in requested data'); 
                $end_date = strtotime($end_date); // time to unix
                $end_date = date("Y-m-d",$end_date); // well formated time

                // working query
                /*
                        SELECT title_id, SUM( CASE WHEN ops = 'expense' THEN amount ELSE 0 END ) AS total_expense, SUM( CASE WHEN ops = 'income' THEN amount ELSE 0 END ) AS total_income FROM finance,title WHERE title_id IS NOT NUll AND finance.title_id = title.id AND title.calculation = 'balance' AND finance.created_date BETWEEN '2020-01-01' AND '2020-03-23' GROUP BY finance.title_id
                */

                $queryData = R::getAll('SELECT title_id, SUM( CASE WHEN ops = :ops_expense THEN amount ELSE 0 END ) AS total_expense, SUM( CASE WHEN ops = :ops_income THEN amount ELSE 0 END ) AS total_income FROM finance,title WHERE title_id IS NOT NUll AND finance.title_id = title.id AND title.calculation = :calculation AND finance.created_date BETWEEN :start_date AND :end_date GROUP BY finance.title_id',
                        array(
                                ':ops_expense'=>'expense',
                                ':ops_income'=>'income',
                                ':calculation'=>'profit',
                                ':start_date'=>$start_date,
                                ':end_date'=>$end_date
                        )
                );
                
                // calculate 
                for($i = 0; $i < count($queryData); $i++){
                        $queryData[$i]['balance'] = $queryData[$i]['total_income'] - $queryData[$i]['total_expense'] ;
                        if($queryData[$i]['balance'] < 0 ) $queryData[$i]['status'] = "lose";
                        else $queryData[$i]['status'] = "profit";
                }

                return_success("profit and lose between ".$start_date." and ".$end_date,$queryData);
                
        }
        public function balance_sheet($data){
                
                //  1. start date
                $start_date = (string) isset($data['start_date']) ? sanitize_str($data['start_date'],"calculation->trail : start_date") :  return_fail('calculation->trail : start_date is not defined in requested data');
                $start_date = strtotime($start_date); // time to unix
                $start_date = date("Y-m-d",$start_date); // well formated time

                //  2. end date
                $end_date = (string) isset($data['end_date']) ? sanitize_str($data['end_date'],"calculation->trail : end_date") :  return_fail('calculation->trail : end_date is not defined in requested data'); 
                $end_date = strtotime($end_date); // time to unix
                $end_date = date("Y-m-d",$end_date); // well formated time
                

                $queryData = R::getAll('SELECT title_id, SUM( CASE WHEN ops = :ops_expense THEN amount ELSE 0 END ) AS total_expense, SUM( CASE WHEN ops = :ops_income THEN amount ELSE 0 END ) AS total_income FROM finance,title WHERE title_id IS NOT NUll AND finance.title_id = title.id AND title.calculation = :calculation AND finance.created_date BETWEEN :start_date AND :end_date GROUP BY finance.title_id',
                        array(
                                ':ops_expense'=>'expense',
                                ':ops_income'=>'income',
                                ':calculation'=>'balance',
                                ':start_date'=>$start_date,
                                ':end_date'=>$end_date
                        )
                );
                
                // calculate 
                for($i = 0; $i < count($queryData); $i++){
                        $queryData[$i]['balance'] = $queryData[$i]['total_income'] - $queryData[$i]['total_expense'] ;
                        //if($queryData[$i]['balance'] < 0 ) $queryData[$i]['status'] = "lose";
                }

                return_success("balance sheet between ".$start_date." and ".$end_date,$queryData);
                
        }
        public function insert($data){
                $this->calculation->name = (string) isset($data['name']) ? sanitize_str($data['name'],"calculation->insert : name") :  return_fail('calculation->insert : name is not defined in requested data');
                try{
                        $id = R::store($this->calculation);
                        return_success("calculation->insert",$this->calculation);
                }catch(Exception $exp){
                        return_fail("calculation->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $calculations = R::find('calculation',' id > ? ', [ $last_id ]);
                else $calculations = R::find('calculation', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                foreach($calculations AS $index=>$calculation){
                        $return_data[] = $calculation;
                }
                return_success("calculation->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('calculation->update : id is not defined in requested data');
                $calculation = R::load( 'calculation', $id );
                if($calculation->id == 0 ) return_fail("calculation->update : no data for requested id");
                $calculation->name = (string) isset($data['name']) ? sanitize_str($data['name'],"calculation->update : name") :  $calculation->name;
                try{
                        R::store($calculation);
                        return_success("calculation->update",$calculation);
                }catch(Exception $exp){
                        return_fail("calculation->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('calculation->delete : id is not defined in requested data');
                $calculation = R::load( 'calculation', $id );
                if($calculation->id == 0 ) return_fail("calculation->delete : no data for requested id");
                try{
                        R::trash($calculation);
                        return_success("calculation->delete",$calculation);
                }catch(Exception $exp){
                        return_fail("calculation->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>