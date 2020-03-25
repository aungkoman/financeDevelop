<?php
		include ('phpqrcode/qrlib.php');
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
						<div style='width:100%;height:20px;font-size:15px;font-weight:bold;padding-left:-20px;'><img src='Images/mehl.png' style='max-width:90px;max-height:90px;position:relative;float:left;'/>Myanmar Economic Holding Public Company Limited</div>
						<div style='width:90%;height:30px;text-align:right;font-size:15px;font-weight:bold;margin-right:10px;padding-top:15px;'>INVOICE</div>
						<div style='width:30%;height:70px;float:right;'>
							<table class='invoiceTbl' style='width:100%;height:100%;float:right;margin-top:15px;margin-right:-20px;margin-right:10px;font-size:12px;border-collapse:collapse;'>
								<tr style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Invoice No:</td><td width='60%' id='invoiceNo' style='text-align:right;'>000553</td></tr>
								<tr  style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Date:</td><td width='60%' id='invoiceDate' style='text-align:right;'>2020-03-20</td></tr>
							</table>
						</div>
						<div style='display:flex;flex-direction:row;align-items:center;justify-content:center;width:100%;height:auto;float:left;margin-top:5px;'>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>FROM</legend>
									<table style='width:100%;position:relative;border-collapse:collapse;font-size:13px;'>
										<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='fromName'>ဒေါ်ဉမာမြင့်</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='fromCompany'>အုပ်ချုပ်စီမံဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='fromIDAdd'>မန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='fromPhNo'>09-43100878</td></tr>
									</table>
								</fieldset>
							</div>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>TO</legend>
									<table style='width:100%;border-collapse:collapse;font-size:13px;'>
											<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='toName'>ဒေါ်သင်းသင်းစော</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='toCompany'>ဘဏ္ဍာရေးနှင့်ငွေစာရင်းဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='toIDAdd'>လက်ထောက်အထွေတွေမန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='toPhNo'>09-43100878</td></tr>
									</table>
								</fieldset>
							</div>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<tr><td width='30%' style='font-weight:bold;'>Purpose of Payment:</td><td  id='purposeOfPayment'width='70%' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>၃/၂၀၂၀ လအတွက်ဝန်ထမ်းသက်သာ ပဒုမ္မာဆပ်ပြာ(၂၄၀)ဂရမ်(၁x၈၀)ဆပ်ပြာ (၂၁)သေတ္တာဖိုးငွေ ပေးသွင်းခြင်း</td></tr>
							</table>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<tr><td width='30%' style='font-weight:bold;'>Amount:</td><td width='70%' id='amount' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>315,000 MMK</td></tr>
							</table>
						</div>
						<p style='font-weight:bold;border-bottom:2px solid gray;float:left;font-size:13px;margin-left:10px;'>Payment Made By</p>
						<table style='width:100%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;'>
							<tr><td><input type='checkbox' style='cursor:pointer;' id='cash'>Cash</td><td><input type='checkbox' style='cursor:pointer;' id='cheque'>Cheque</td><td><input type='checkbox'style='cursor:pointer;' id='accToAcc'>AccountToAccount</td><td><input type='checkbox' style='cursor:pointer;' id='slip'>Slip</td><tr>
						</table>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<tr><td width='30%' style='font-weight:bold;'>Description:</td><td width='70%' id='decription' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>MWD-11-740 20-3-2020</td></tr>
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
								$string = "InvoiceNo:000553&Date:2020-03-20&Amount:315,000 MMK&paymentType:Slip";
								
								$path = 'Images/'; 
								$file = $path.uniqid().".png"; 
								$ecc = 'L'; 
								$pixel_Size = 5; 
								$frame_Size = 5;   
								QRcode::png($string, $file, $ecc, $pixel_Size, $frame_Size); 
								echo "<center><img src='".$file."' style='width:130px;height:130px;margin-top:-60px;margin-left:20%;'></center>";
							?>
					  </div>
					</div>
					<div class='printFormContainer' style='background-color:#fefefe;'>
						<div style='width:100%;height:20px;font-size:15px;font-weight:bold;padding-left:-20px;'><img src='Images/mehl.png' style='max-width:90px;max-height:90px;position:relative;float:left;'/>Myanmar Economic Holding Public Company Limited</div>
						<div style='width:90%;height:30px;text-align:right;font-size:15px;font-weight:bold;margin-right:10px;padding-top:15px;'>INVOICE</div>
						<div style='width:30%;height:70px;float:right;'>
							<table class='invoiceTbl' style='width:100%;height:100%;float:right;margin-top:15px;margin-right:-20px;margin-right:10px;font-size:12px;border-collapse:collapse;'>
								<tr style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Invoice No:</td><td width='60%' id='invoiceNo' style='text-align:right;'>000553</td></tr>
								<tr  style='border-bottom:1px dotted gray;'><td width='40%' style='font-weight:bold;'>Date:</td><td width='60%' id='invoiceDate' style='text-align:right;'>2020-03-20</td></tr>
							</table>
						</div>
						<div style='display:flex;flex-direction:row;align-items:center;justify-content:center;width:100%;height:auto;float:left;margin-top:5px;'>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>FROM</legend>
									<table style='width:100%;position:relative;border-collapse:collapse;font-size:13px;'>
										<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='fromName'>ဒေါ်ဉမာမြင့်</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='fromCompany'>အုပ်ချုပ်စီမံဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='fromIDAdd'>မန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='fromPhNo'>09-43100878</td></tr>
									</table>
								</fieldset>
							</div>
							<div style='flex:1;height:100%;'>
								<fieldset style='width:100%:height:100%;border:none;margin-top:10px;'>
									<legend style='color:;font-weight:bold;border-bottom:2px solid gray;font-size:13px;'>TO</legend>
									<table style='width:100%;border-collapse:collapse;font-size:13px;'>
											<tr style='border-bottom:1px dotted gray;'><td>[Name]</td><td style='wordwrap:wrap;' id='toName'>ဒေါ်သင်းသင်းစော</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td width='20%'>[CompanyName]</td ><td width='70%' style='word-wrap:break-word;' id='toCompany'>ဘဏ္ဍာရေးနှင့်ငွေစာရင်းဌာန</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[ID/Add:]</td><td style='word-wrap:break-word;' id='toIDAdd'>လက်ထောက်အထွေတွေမန်နေဂျာ</td></tr>
										<tr style='border-bottom:1px dotted gray;'><td>[PhoneNo:]</td><td style='word-wrap:break-word;' id='toPhNo'>09-43100878</td></tr>
									</table>
								</fieldset>
							</div>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<tr><td width='30%' style='font-weight:bold;'>Purpose of Payment:</td><td  id='purposeOfPayment'width='70%' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>၃/၂၀၂၀ လအတွက်ဝန်ထမ်းသက်သာ ပဒုမ္မာဆပ်ပြာ(၂၄၀)ဂရမ်(၁x၈၀)ဆပ်ပြာ (၂၁)သေတ္တာဖိုးငွေ ပေးသွင်းခြင်း</td></tr>
							</table>
						</div>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<tr><td width='30%' style='font-weight:bold;'>Amount:</td><td width='70%' id='amount' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>315,000 MMK</td></tr>
							</table>
						</div>
						<p style='font-weight:bold;border-bottom:2px solid gray;float:left;font-size:13px;margin-left:10px;'>Payment Made By</p>
						<table style='width:100%;height:auto;margin-left:10px;border-collapse:collapse;margin-top:5px;'>
							<tr><td><input type='checkbox' style='cursor:pointer;' id='cash'>Cash</td><td><input type='checkbox' style='cursor:pointer;' id='cheque'>Cheque</td><td><input type='checkbox'style='cursor:pointer;' id='accToAcc'>AccountToAccount</td><td><input type='checkbox' style='cursor:pointer;' id='slip'>Slip</td><tr>
						</table>
						<div style='width:100%;height:auto;float:left;margin-left:10px;margin-top:5px;'>
							<table style='width:100%;height:auto;border-collapse:collapse;font-size:13px;'>
								<tr><td width='30%' style='font-weight:bold;'>Description:</td><td width='70%' id='decription' height='auto' style='border-bottom:2px dotted gray;word-wrap:break-word;'>MWD-11-740 20-3-2020</td></tr>
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
								$string = "InvoiceNo:000553&Date:2020-03-20&Amount:315,000 MMK&paymentType:Slip";
								/*$bytes = array();
								for($i = 0; $i < strlen($string); $i++){
									$bytes[] = ord($string[$i]);
								}
								$str=implode(",",$bytes);*/
								$path = 'Images/'; 
								$file = $path.uniqid().".png"; 
								$ecc = 'L'; 
								$pixel_Size = 5; 
								$frame_Size = 5;   
								QRcode::png($string, $file, $ecc, $pixel_Size, $frame_Size); 
								echo "<center><img src='".$file."' style='width:130px;height:130px;margin-top:-60px;margin-left:20%;'></center>";
							?>
					  </div>
					</div>
			</div>
	</body>
</html>