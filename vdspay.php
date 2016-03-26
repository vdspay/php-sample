<?php 

//Credentials
$accountNo = ""; //Account Number.
$api_key = ""; //API Key 

  //Prepare Post
  $post = array();
  $post["transaction"]["accountNo"] = "XXXXXXXXXX"; //VdsPay Account Number
  $post["transaction"]["memo"] = $_POST["memo"];
  $post["transaction"]["reference"] = "000022558";
  $post["transaction"]["amount"] = $_POST["amount"];
  $post["transaction"]["currency"] = "USD";
  $post["transaction"]["type"] = "Sale";
  $post["transaction"]["return_url"] = ""; //Return URL
  $post["transaction"]["notify_url"] = ""; //Notify URL
  $post["transaction"]["customer"]["name"] = $_POST["customer_name"];
  $post["transaction"]["customer"]["email"] = $_POST["customer_email"];
  $post["transaction"]["customer"]["phone"] = $_POST["customer_phone"];
  $post_data = json_encode($post, true);
  
  //Calculate Hash
  $hash = hash("sha512", $accountNo.$post["transaction"]["reference"].$_POST["amount"].$api_key);
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://acs.vdspay.net/transaction/auth");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($post_data),
	  'Authorization: Merchant '.$hash.'')                                                                     
    ); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $c = curl_exec($ch);
  $res = json_decode($c, true);
  if($res["message"] == "Authorization URL created") {
    $url = $res["data"]["authorization_url"];
    header("Location: $url");
    exit();
  } else {
		$OutPut = $res["message"];
		echo $OutPut;
	}
