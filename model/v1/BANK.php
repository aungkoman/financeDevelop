<?php
class BANK{
        public $bank;
        public function __construct(){
                $this->bank = R::dispense('bank');
        }
        public function insert($data){
                $this->bank->name = (string) isset($data['name']) ? sanitize_str($data['name'],"bank->insert : name") :  return_fail('bank->insert : name is not defined in requested data');
                try{
                        $id = R::store($this->bank);
                        return_success("bank->insert",$this->bank);
                }catch(Exception $exp){
                        return_fail("bank->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $banks = R::find('bank',' id > ? ', [ $last_id ]);
                else $banks = R::find('bank', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                foreach($banks AS $index=>$bank){
                        $return_data[] = $bank;
                }
                return_success("bank->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('bank->update : id is not defined in requested data');
                $bank = R::load( 'bank', $id );
                if($bank->id == 0 ) return_fail("bank->update : no data for requested id");
                $bank->name = (string) isset($data['name']) ? sanitize_str($data['name'],"bank->update : name") :  $bank->name;
                try{
                        R::store($bank);
                        return_success("bank->update",$bank);
                }catch(Exception $exp){
                        return_fail("bank->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('bank->delete : id is not defined in requested data');
                $bank = R::load( 'bank', $id );
                if($bank->id == 0 ) return_fail("bank->delete : no data for requested id");
                try{
                        R::trash($bank);
                        return_success("bank->delete",$bank);
                }catch(Exception $exp){
                        return_fail("bank->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>