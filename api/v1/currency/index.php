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
include('../../../model/v1/CURRENCY.php'); // currency Model
$currency = new CURRENCY(); // create new currency object
$method = $_SERVER['REQUEST_METHOD']; // get request method
// middleware_super_user($_POST); // middleware usage 
switch ($method) {
	case 'POST':
        $request_data = $_POST;
        $ops_type = (string) isset($request_data['ops_type']) ? sanitize_str($request_data['ops_type'],"currency->ops_type") :  return_fail('currency->ops_type : ops_type is not defined in requested data'); // ops_type sanitize string
        switch ($ops_type){
            case 'insert':
                $currency->insert($request_data);
                break;
            case 'select':
                $currency->select($request_data);
                break;
            case 'update':
                $currency->update($request_data);
                break;
            case 'delete':
                $currency->delete($request_data);
                break;
            default :
                return_fail('currency : unknow_ops_type',$ops_type);
                break;
        }
        break;
	default:
		# code...
		//echo "undefined method =>".$method;
		return_fail("currency : unknow_method",$method);
		break;
}
?>