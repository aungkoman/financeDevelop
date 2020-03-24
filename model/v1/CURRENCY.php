<?php
class CURRENCY{
        public $currency;
        public function __construct(){
                $this->currency = R::dispense('currency');
        }
        public function insert($data){
                $this->currency->name = (string) isset($data['name']) ? sanitize_str($data['name'],"currency->insert : name") :  return_fail('currency->insert : name is not defined in requested data');
                try{
                        $id = R::store($this->currency);
                        return_success("currency->insert",$this->currency);
                }catch(Exception $exp){
                        return_fail("currency->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $currencys = R::find('currency',' id > ? ', [ $last_id ]);
                else $currencys = R::find('currency', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                foreach($currencys AS $index=>$currency){
                        $return_data[] = $currency;
                }
                return_success("currency->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('currency->update : id is not defined in requested data');
                $currency = R::load( 'currency', $id );
                if($currency->id == 0 ) return_fail("currency->update : no data for requested id");
                $currency->name = (string) isset($data['name']) ? sanitize_str($data['name'],"currency->update : name") :  $currency->name;
                try{
                        R::store($currency);
                        return_success("currency->update",$currency);
                }catch(Exception $exp){
                        return_fail("currency->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('currency->delete : id is not defined in requested data');
                $currency = R::load( 'currency', $id );
                if($currency->id == 0 ) return_fail("currency->delete : no data for requested id");
                try{
                        R::trash($currency);
                        return_success("currency->delete",$currency);
                }catch(Exception $exp){
                        return_fail("currency->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>