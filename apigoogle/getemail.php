<?php
define('TOKENGOOGLE',$tokendata[‘token_access’]);
 
function get_userEmail($token){
	$email = '';
 
	$url = 'https://www.googleapis.com/oauth2/v1/userinfo';
	$url .= '?access_token='.$token;
 
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1); 
	$result_curl = curl_exec($ch);
	$error_curl = curl_error($ch);
	curl_close($ch);
 
	$res = json_decode($result_curl);
 
	if ($res->email){
		$email = $res->email;
	}
	return $email;
}
 
$email = get_userEmail(TOKENGOOGLE);
echo $email;
echo "hola";
?>