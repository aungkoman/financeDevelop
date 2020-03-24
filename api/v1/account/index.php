<?php
ini_set("allow_url_fopen", true); 
header("Access-Control-Allow-Origin: *"); // we allow cross origin request
header("Content-Type: application/json; charset=UTF-8"); // we return JSON in UTF-8 
header("Access-Control-Allow-Methods: POST"); // we only allow POST request :D
require '../../../vendor/autoload.php'; // initialize composer library
include('../../../lib/rb.php'); // Redbean Database
include('../../../config/conn.php'); // Redbean Database Insatnce initialization
include('../../../config/return_function.php'); // final return functions
include('../../../config/sanitize.php'); // sanitize user input
include('../../../middleware/user_middleware.php'); // User Middleware
include('../../../model/v1/ACCOUNT.php'); // account Model
$account = new ACCOUNT(); // create new account object
$method = $_SERVER['REQUEST_METHOD']; // get request method
// middleware_super_user($_POST); // middleware usage 
switch ($method) {
	case 'POST':
        $request_data = $_POST;
        $ops_type = (string) isset($request_data['ops_type']) ? sanitize_str($request_data['ops_type'],"account->ops_type") :  return_fail('account->ops_type : ops_type is not defined in requested data'); // ops_type sanitize string
        switch ($ops_type){
            case 'insert':
                /* what we have to do?
                    1. insert new account and get inserted_id
                    2. insert account_transaction
                    3. return success , yes just succes coz we already die error during exection
                    no no 
                    Just break the controller and work in model :D 
                    Coz we alredy knew the constraint for each operiation
                    such as every insertion has finance opening balance log
                */
                $account->insert($request_data);
                break;
            case 'select':
                $account->select($request_data);
                break;
            case 'update':
                $account->update($request_data);
                break;
            case 'delete':
                $account->delete($request_data);
                break;
            default :
                return_fail('account : unknow_ops_type',$ops_type);
                break;
        }
        break;
	default:
		# code...
		//echo "undefined method =>".$method;
		return_fail("account : unknow_method",$method);
		break;
}
?>