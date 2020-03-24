<?php
class AUTH{
        public $auth;
        public function __construct(){
                $this->auth = R::dispense('auth');
        }
        public function insert($data){
                $this->auth->name = (string) isset($data['name']) ? sanitize_str($data['name'],"auth->insert : name") :  return_fail('auth->insert : name is not defined in requested data');
                try{
                        $id = R::store($this->auth);
                        return_success("auth->insert",$this->auth);
                }catch(Exception $exp){
                        return_fail("auth->insert : exception ",$exp->getMessage());
                }
        }
        public function select($data){
                $limit = (int) isset($data['limit']) ? sanitize_int($data['limit']) : 0;
                $last_id = (int) isset($data['last_id']) ? sanitize_int($data['last_id']) : 0;
                if($limit == 0 ) $auths = R::find('auth',' id > ? ', [ $last_id ]);
                else $auths = R::find('auth', ' id > ? LIMIT ?', [ $last_id, $limit ] );
                $return_data = array();
                foreach($auths AS $index=>$auth){
                        $return_data[] = $auth;
                }
                return_success("auth->select ".count($return_data),$return_data);
        }
        public function update($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('auth->update : id is not defined in requested data');
                $auth = R::load( 'auth', $id );
                if($auth->id == 0 ) return_fail("auth->update : no data for requested id");
                $auth->name = (string) isset($data['name']) ? sanitize_str($data['name'],"auth->update : name") :  $auth->name;
                try{
                        R::store($auth);
                        return_success("auth->update",$auth);
                }catch(Exception $exp){
                        return_fail("auth->update : exception",$exp->getMessage());
                }
        }
        public function delete($data){
                $id = (int) isset($data['id']) ? sanitize_int($data['id']) :  return_fail('auth->delete : id is not defined in requested data');
                $auth = R::load( 'auth', $id );
                if($auth->id == 0 ) return_fail("auth->delete : no data for requested id");
                try{
                        R::trash($auth);
                        return_success("auth->delete",$auth);
                }catch(Exception $exp){
                        return_fail("auth->delete : exception",$exp->getMessage());
                }
        }
}// end for class
?>