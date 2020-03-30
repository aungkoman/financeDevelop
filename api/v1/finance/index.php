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
include('../../../model/v1/FINANCE.php'); // finance Model
$finance = new FINANCE(); // create new finance object
$method = $_SERVER['REQUEST_METHOD']; // get request method
// middleware_super_user($_POST); // middleware usage 
switch ($method) {
	case 'POST':
        $request_data = $_POST;
        $ops_type = (string) isset($request_data['ops_type']) ? sanitize_str($request_data['ops_type'],"finance->ops_type") :  return_fail('finance->ops_type : ops_type is not defined in requested data'); // ops_type sanitize string
        switch ($ops_type){
            case 'insert':
                // 1. upload file to /upload
                // 2. save file name to finance by modifying case_file 
                /*
                    echo "request_data";
                    print_r($request_data);
                    echo "FILES";
                    print_r($_FILES);
                */
                // uniqid(prefix,more_entropy)
                $file_name = uniqid("finance_",true).basename($_FILES["case_file"]["name"]);
                $upload_directory = "../../../upload/".$file_name;

                // check file mime type
                // check file size 
                // limit and validate file information

                $file_type = mime_content_type($_FILES["case_file"]["tmp_name"]);
                if($file_type != "application/pdf") return_fail("finance->insert : you can only upload pdf file.");
                
                if (move_uploaded_file($_FILES["case_file"]["tmp_name"], $upload_directory)) {
                    //echo "The file ". basename( $_FILES["case_file"]["name"]). " has been uploaded.";
                    $request_data['case_file'] = $file_name;
                } else {
                    //echo "Sorry, there was an error uploading your file.";
                    // TDL :
                    // diagnanosis file upload error 
                    return_fail("finance->insert : file upload failed");
                }
                $finance->insert($request_data);
                break;
            case 'select':
                $finance->select($request_data);
                break;
            case 'update':
                $finance->update($request_data);
                break;
            case 'delete':
                $finance->delete($request_data);
                break;
            default :
                return_fail('finance : unknow_ops_type',$ops_type);
                break;
        }
        break;
	default:
		# code...
		//echo "undefined method =>".$method;
		return_fail("finance : unknow_method",$method);
		break;
}
?>