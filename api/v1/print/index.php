<?php
		ini_set("allow_url_fopen", true); 
		header("Access-Control-Allow-Origin: *"); // we allow cross origin request
		header("Content-Type: text/html; charset=UTF-8"); // we return JSON in UTF-8 
		header("Access-Control-Allow-Methods: POST"); // we only allow POST request :D
		require '../../../vendor/autoload.php'; // initialize composer library
		include('../../../lib/rb.php'); // Redbean Database
		include('../../../config/conn.php'); // Redbean Database Insatnce initialization
		include('../../../config/return_function.php'); // final return functions
		include('../../../config/sanitize.php'); // sanitize user input
		include('../../../middleware/user_middleware.php'); // User Middleware

		include ('phpqrcode/qrlib.php');
		/*
			TDL :
			  we have to retrieve data from server
			  we just use id from requested data
			  it's not reliable data to print out 
		*/
		$finance = isset($_POST['finance']) ? $_POST['finance'] : return_fail('finance  is not defined in requested data');
		$finance = json_decode($finance,true);
		// echo "<p>";
		// print_r($finance);
		// echo "</p>";

		$from_name = isset($finance['from_name']) ? sanitize_str($finance['from_name'],"print : from_name ") : return_fail('print : from_name is not defined in requested data');

		$from_company = isset($finance['from_company']) ? sanitize_str($finance['from_company'],"print : from_company ") : return_fail('print : from_company is not defined in requested data');

		$from_address = isset($finance['from_address']) ? sanitize_str($finance['from_name'],"print : from_name ") : return_fail('print : from_address is not defined in requested data');

		$from_phone = isset($finance['from_phone']) ? sanitize_str($finance['from_phone'],"print : from_phone ") : return_fail('print : from_phone is not defined in requested data');

		$to_name = isset($finance['to_name']) ? sanitize_str($finance['to_name'],"print : to_name ") : return_fail('print : to_name is not defined in requested data');

		$to_company = isset($finance['to_company']) ? sanitize_str($finance['to_company'],"print : to_company ") : return_fail('print : to_company is not defined in requested data');

		$to_address = isset($finance['to_address']) ? sanitize_str($finance['to_name'],"print : to_name ") : return_fail('print : to_address is not defined in requested data');

		$to_phone = isset($finance['to_phone']) ? sanitize_str($finance['to_phone'],"print : to_phone ") : return_fail('print : to_phone is not defined in requested data');

		$description = isset($finance['description']) ? sanitize_str($finance['description'],"print : to_phone ") : return_fail('print : description is not defined in requested data');

		$amount = isset($finance['amount']) ? sanitize_int($finance['amount'],"print : amount ") : return_fail('print : amount is not defined in requested data');

		$currency = isset($finance['title']['currency']['name']) ? sanitize_str($finance['title']['currency']['name'],"print : currency ") : return_fail('print : currency is not defined in requested data');

		$payment_method = isset($finance['payment_method']) ? sanitize_str($finance['payment_method'],"print : payment_method ") : return_fail('print : payment_method is not defined in requested data');

		$payment_data = isset($finance['payment_data']) ? sanitize_str($finance['payment_data'],"print : payment_data ") : return_fail('print : payment_data is not defined in requested data');

		$id = isset($finance['id']) ? sanitize_str($finance['id'],"print : id ") : return_fail('print : id is not defined in requested data');
		$id = $id."";
		// fixed num to str 2 
		for($i = 1 ; $i < 7 ; $i++){
			if(strlen($id) <= 5) $id = "0".$id;
		}

		$created_date = isset($finance['created_date']) ? sanitize_str($finance['created_date'],"print : created_date ") : return_fail('print : created_date is not defined in requested data');


		// $path = 'myfolder/myimage.png';
		// $type = pathinfo($path, PATHINFO_EXTENSION);
		// $data = file_get_contents($path);
		// $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		$logo_path = 'Images/mehl.png';
		$logo_type = pathinfo($logo_path, PATHINFO_EXTENSION);
		$logo_data = file_get_contents($logo_path);
		$logo_data_image = 'data:image/' . $logo_type . ';base64,' . base64_encode($logo_data);

		$string = "InvoiceNo:".$id."&Date:".$created_date."&Amount:".$amount." ".$currency."&paymentType:".$payment_method."";
								
		$path = 'Images/'; 
		$file = $path.uniqid().".png"; 
		$ecc = 'L'; 
		$pixel_Size = 5; 
		$frame_Size = 5;   
		QRcode::png($string, $file, $ecc, $pixel_Size, $frame_Size); 

		
		$qr_path = $file;
		$qr_type = pathinfo($qr_path, PATHINFO_EXTENSION);
		$qr_data = file_get_contents($qr_path);
		$qr_data_image = 'data:image/' . $qr_type . ';base64,' . base64_encode($qr_data);

		// we need to delete the file
		try{
			unlink($qr_path);
			//echo "delete ".$qr_path;
		}
		catch(Exception $exp){
			//echo "delete exception : ".$exp->getMessage();
		}
		

		// sanitize_str($data['name'],"account->insert : name") :  return_fail('account->insert : name is not defined in requested data');
?>
<!DOCTYPE html>
<html>
	<head>
		<title style='cursor:pointer;'>MEHL Finance Print Preview</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="images/logo.png" type="image/png">
		<script src='script/jquery.js'></script>
		<script src='script/jquery.min.js'></script>
		<style>
			html,body{
				margin:0px;
				padding:0px;
			}
			*{
				font-family:myanmar3;
			}
			@font-face {
			  font-family:myanmar3;
			  src: url(font/myanmar3.ttf);
			}
			@-webkit-keyframes fade {
				 from {transform: scale(0.1)} 
				to {transform: scale(1)}
			}
			@keyframes fade{
				from {transform: scale(0.1)} 
				to {transform: scale(1)}
			}
			body::-webkit-scrollbar{width: 7px; height:1px;transition:all .5s}
			body::-webkit-scrollbar-track{
						background: -webkit-gradient(linear, center top, center bottom, from(#b0bac7), to(#fff));
						background-image: linear-gradient(gray, #fff);
						box-shadow: inset 0px 0px 3px 3px rgba(0,0,0,0.1)
						border-radius:15px;
				}
			body::-webkit-scrollbar-thumb{
				background-color:#ff5500;
				border-radius:15px;
				color:#eeeeee;
			}
			.showResultContainer{
				position:absolute;
				display:flex;
				justify-content:space-between;
				align-items:center;
				width:100%;
				height:100%;
				background-color:transparent;
				align-items: stretch;
				flex-direction:row;
			}
			.printFormContainer{
				flex:1;
				
			}
		</style>
		<script type='text/javascript'>
			$(document).ready(function(){
				//alert("Ready...");
			});
		</script>
	</head>
	<body>
			<div class='showResultContainer'>
					<div class='printFormContainer' style='border-right:2px dotted gray;'>
							<!--<img src='Images/mehl.png' style='max-width:80px;max-height:80px;position:relative;float:left;'/><p style='font-size:18px;font-weight:bold;padding-left:-120px;float:left;'>Myanmar Economic Holding Public Company Limited</p>
							-->
						<div style='width:100%;height:20px;font-size:15px;font-weight:bold;padding-left:-20px;'>
						
						<!--img src='Images/mehl.png' style='max-width:90px;max-height:90px;position:relative;float:left;'/-->
						<img src='<?php echo $logo_data_image; ?>' style='max-width:90px;max-height:90px;position:relative;float:left;'/>
						
						Myanmar Economic Holding Public Company Limited</div>
						<div style='width:90%;height:30px;text-align:right;font-size:15px;font-weight:bold;margin-right:10px;padding-top:15px;'>INVOICE</div>
						<div style='width:30%;height:70px;float:right;'>
							<table class='invoiceTbl' style='width:100%;height:100%;float:right;margin-top:15px;margin-right:-20px;margin-right:10px;font-size:12px;border-collapse:collapse;'>
								<!--tr style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Invoice No:</td><td width='60%' id='invoiceNo' style='text-align:right;'>000553</td></tr>
								<tr  style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Date:</td><td width='60%' id='invoiceDate' style='text-align:right;'>2020-03-20</td></tr-->

								<tr style='border-bottom:1px dotted gray;'>
									<td width='40%' style='font-weight:bold;'>Invoice No:</td>
									<td width='60%' id='invoiceNo' style='text-align:right;'>
										<?php echo $id; ?>
									</td>
								</tr>
								<tr  style='border-bottom:1px dotted gray;'>
									<td width='40%' style='font-weight:bold;'>Date:</td>
									<td width='60%' id='invoiceDate' style='text-align:right;'>
									<?php echo $created_date; ?>
									</td>
								</tr>


							</table>
						</div>
						<div style='display:flex;flex-direction:row;align-items:center;justify-content:center;width:100%;height:auto;float:left;margin-top:5px;'>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>FROM</legend>
									<table style='width:100%;position:relative;border-collapse:collapse;font-size:13px;'>
										<!--tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='fromName'>ဒေါ်ဉမာမြင့်</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='fromCompany'>အုပ်ချုပ်စီမံဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='fromIDAdd'>မန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='fromPhNo'>09-43100878</td></tr-->

										<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='fromName'><?php echo $from_name; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='fromCompany'><?php echo $from_company; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='fromIDAdd'><?php echo $from_address; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='fromPhNo'><?php echo $from_phone; ?></td></tr>


									</table>
								</fieldset>
							</div>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>TO</legend>
									<table style='width:100%;border-collapse:collapse;font-size:13px;'>
											<!--tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='toName'>ဒေါ်သင်းသင်းစော</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='toCompany'>ဘဏ္ဍာရေးနှင့်ငွေစာရင်းဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='toIDAdd'>လက်ထောက်အထွေတွေမန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='toPhNo'>09-43100878</td></tr-->

										<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='toName'><?php echo $to_name; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='toCompany'><?php echo $to_company; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='toIDAdd'><?php echo $to_address; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='toPhNo'><?php echo $to_phone; ?></td></tr>
									</table>
								</fieldset>
							</div>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<!--tr><td width='30%' style='font-weight:bold;'>Purpose of Payment:</td><td  id='purposeOfPayment'width='70%' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>၃/၂၀၂၀ လအတွက်ဝန်ထမ်းသက်သာ ပဒုမ္မာဆပ်ပြာ(၂၄၀)ဂရမ်(၁x၈၀)ဆပ်ပြာ (၂၁)သေတ္တာဖိုးငွေ ပေးသွင်းခြင်း</td></tr-->

								<tr><td width='30%' style='font-weight:bold;'>Purpose of Payment:</td><td  id='purposeOfPayment'width='70%' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'><?php echo $description; ?></td></tr>

							</table>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<!--tr><td width='30%' style='font-weight:bold;'>Amount:</td><td width='70%' id='amount' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>315,000 MMK</td></tr-->
								
								<tr>
									<td width='30%' style='font-weight:bold;'>Amount:</td><td width='70%' id='amount' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>
										<?php echo $amount." ".$currency; ?>
									</td>
								</tr>
							</table>
						</div>
						<p style='font-weight:bold;border-bottom:2px solid gray;float:left;font-size:13px;margin-left:10px;'>Payment Made By</p>
						<table style='width:100%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;'>
							<!--tr>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cash'>
									Cash
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cheque'>
									Cheque
								</td>
								<td>
									<input type='checkbox'style='cursor:pointer;' id='accToAcc'>
									AccountToAccount
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='slip'>
									Slip
								</td>
							<tr-->
							<tr>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cash' disabled <?php if($payment_method == "cash") echo 'checked'; ?> >									Cash
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cheque' disabled <?php if($payment_method == "cheque") echo 'checked'; ?> >
									Cheque
								</td>
								<td>
									<input type='checkbox'style='cursor:pointer;' id='accToAcc' disabled <?php if($payment_method == "transfer") echo 'checked'; ?> >
									AccountToAccount
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='slip' disabled <?php if($payment_method == "slip") echo 'checked'; ?> >
									Slip
								</td>
							<tr>
						</table>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<!--tr><td width='30%' style='font-weight:bold;'>Description:</td><td width='70%' id='decription' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>MWD-11-740 20-3-2020</td></tr-->

								<tr>
									<td width='30%' style='font-weight:bold;'>Description:</td>
									<td width='70%' id='decription' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>
										<?php echo $payment_data; ?>
									</td>
								</tr>

							</table>
						</div>
						<div style='width:100%;height:auto;float:left;margin-top:15px;'>
							<table style='width:45%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;margin-right:10px;float:left;'>
								<tr style='height:50px;'><td style='width:50%;border-bottom:2px dotted gray;border-right:trasparent;'></td><tr>
								<tr style='height:20px;'><td style='width:50%;text-align:center;font-weight:bold;'>(Authorized Signature)</td><tr>
							</table>
							<table style='width:45%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;margin-right:10px;float:left;margin-left:15px;'>
								<tr style='height:50px;'><td style='width:50%;;border-bottom:2px dotted gray;border-right:trasparent;'></td><tr>
								<tr style='height:20px;'><td style='width:50%;text-align:center;font-weight:bold;'>(Authorized Signature)</td><tr>
							</table>
						</div>
						<div class='barcode' style='width:200px;height:60px;position:fixed;bottom:0;'>
							<?php
								
								//echo "<center><img src='".$file."' style='width:130px;height:130px;margin-top:-60px;margin-left:20%;'></center>";
								echo "<center><img src='".$qr_data_image."' style='width:130px;height:130px;margin-top:-60px;margin-left:20%;'></center>";
							?>
					  </div>
					</div>

					
					<div class='printFormContainer' style='border-right:2px dotted gray;'>
							<!--<img src='Images/mehl.png' style='max-width:80px;max-height:80px;position:relative;float:left;'/><p style='font-size:18px;font-weight:bold;padding-left:-120px;float:left;'>Myanmar Economic Holding Public Company Limited</p>
							-->
						<div style='width:100%;height:20px;font-size:15px;font-weight:bold;padding-left:-20px;'>
						
						<!--img src='Images/mehl.png' style='max-width:90px;max-height:90px;position:relative;float:left;'/-->
						<img src='<?php echo $logo_data_image; ?>' style='max-width:90px;max-height:90px;position:relative;float:left;'/>
						
						Myanmar Economic Holding Public Company Limited</div>
						<div style='width:90%;height:30px;text-align:right;font-size:15px;font-weight:bold;margin-right:10px;padding-top:15px;'>INVOICE</div>
						<div style='width:30%;height:70px;float:right;'>
							<table class='invoiceTbl' style='width:100%;height:100%;float:right;margin-top:15px;margin-right:-20px;margin-right:10px;font-size:12px;border-collapse:collapse;'>
								<!--tr style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Invoice No:</td><td width='60%' id='invoiceNo' style='text-align:right;'>000553</td></tr>
								<tr  style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Date:</td><td width='60%' id='invoiceDate' style='text-align:right;'>2020-03-20</td></tr-->

								<tr style='border-bottom:1px dotted gray;'>
									<td width='40%' style='font-weight:bold;'>Invoice No:</td>
									<td width='60%' id='invoiceNo' style='text-align:right;'>
										<?php echo $id; ?>
									</td>
								</tr>
								<tr  style='border-bottom:1px dotted gray;'>
									<td width='40%' style='font-weight:bold;'>Date:</td>
									<td width='60%' id='invoiceDate' style='text-align:right;'>
									<?php echo $created_date; ?>
									</td>
								</tr>


							</table>
						</div>
						<div style='display:flex;flex-direction:row;align-items:center;justify-content:center;width:100%;height:auto;float:left;margin-top:5px;'>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>FROM</legend>
									<table style='width:100%;position:relative;border-collapse:collapse;font-size:13px;'>
										<!--tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='fromName'>ဒေါ်ဉမာမြင့်</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='fromCompany'>အုပ်ချုပ်စီမံဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='fromIDAdd'>မန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='fromPhNo'>09-43100878</td></tr-->

										<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='fromName'><?php echo $from_name; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='fromCompany'><?php echo $from_company; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='fromIDAdd'><?php echo $from_address; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='fromPhNo'><?php echo $from_phone; ?></td></tr>


									</table>
								</fieldset>
							</div>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>TO</legend>
									<table style='width:100%;border-collapse:collapse;font-size:13px;'>
											<!--tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='toName'>ဒေါ်သင်းသင်းစော</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='toCompany'>ဘဏ္ဍာရေးနှင့်ငွေစာရင်းဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='toIDAdd'>လက်ထောက်အထွေတွေမန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='toPhNo'>09-43100878</td></tr-->

										<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='toName'><?php echo $to_name; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='toCompany'><?php echo $to_company; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='toIDAdd'><?php echo $to_address; ?></td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='toPhNo'><?php echo $to_phone; ?></td></tr>
									</table>
								</fieldset>
							</div>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<!--tr><td width='30%' style='font-weight:bold;'>Purpose of Payment:</td><td  id='purposeOfPayment'width='70%' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>၃/၂၀၂၀ လအတွက်ဝန်ထမ်းသက်သာ ပဒုမ္မာဆပ်ပြာ(၂၄၀)ဂရမ်(၁x၈၀)ဆပ်ပြာ (၂၁)သေတ္တာဖိုးငွေ ပေးသွင်းခြင်း</td></tr-->

								<tr><td width='30%' style='font-weight:bold;'>Purpose of Payment:</td><td  id='purposeOfPayment'width='70%' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'><?php echo $description; ?></td></tr>

							</table>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<!--tr><td width='30%' style='font-weight:bold;'>Amount:</td><td width='70%' id='amount' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>315,000 MMK</td></tr-->
								
								<tr>
									<td width='30%' style='font-weight:bold;'>Amount:</td><td width='70%' id='amount' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>
										<?php echo $amount." ".$currency; ?>
									</td>
								</tr>
							</table>
						</div>
						<p style='font-weight:bold;border-bottom:2px solid gray;float:left;font-size:13px;margin-left:10px;'>Payment Made By</p>
						<table style='width:100%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;'>
							<!--tr>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cash'>
									Cash
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cheque'>
									Cheque
								</td>
								<td>
									<input type='checkbox'style='cursor:pointer;' id='accToAcc'>
									AccountToAccount
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='slip'>
									Slip
								</td>
							<tr-->
							<tr>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cash' disabled <?php if($payment_method == "cash") echo 'checked'; ?> >									Cash
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='cheque' disabled <?php if($payment_method == "cheque") echo 'checked'; ?> >
									Cheque
								</td>
								<td>
									<input type='checkbox'style='cursor:pointer;' id='accToAcc' disabled <?php if($payment_method == "transfer") echo 'checked'; ?> >
									AccountToAccount
								</td>
								<td>
									<input type='checkbox' style='cursor:pointer;' id='slip' disabled <?php if($payment_method == "slip") echo 'checked'; ?> >
									Slip
								</td>
							<tr>
						</table>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<!--tr><td width='30%' style='font-weight:bold;'>Description:</td><td width='70%' id='decription' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>MWD-11-740 20-3-2020</td></tr-->

								<tr>
									<td width='30%' style='font-weight:bold;'>Description:</td>
									<td width='70%' id='decription' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>
										<?php echo $payment_data; ?>
									</td>
								</tr>

							</table>
						</div>
						<div style='width:100%;height:auto;float:left;margin-top:15px;'>
							<table style='width:45%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;margin-right:10px;float:left;'>
								<tr style='height:50px;'><td style='width:50%;border-bottom:2px dotted gray;border-right:trasparent;'></td><tr>
								<tr style='height:20px;'><td style='width:50%;text-align:center;font-weight:bold;'>(Authorized Signature)</td><tr>
							</table>
							<table style='width:45%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;margin-right:10px;float:left;margin-left:15px;'>
								<tr style='height:50px;'><td style='width:50%;;border-bottom:2px dotted gray;border-right:trasparent;'></td><tr>
								<tr style='height:20px;'><td style='width:50%;text-align:center;font-weight:bold;'>(Authorized Signature)</td><tr>
							</table>
						</div>
						<div class='barcode' style='width:200px;height:60px;position:fixed;bottom:0;'>
							<?php
								
								//echo "<center><img src='".$file."' style='width:130px;height:130px;margin-top:-60px;margin-left:20%;'></center>";
								echo "<center><img src='".$qr_data_image."' style='width:130px;height:130px;margin-top:-60px;margin-left:20%;'></center>";
							?>
					  </div>
					</div>
					
					
			</div>
	</body>
</html>