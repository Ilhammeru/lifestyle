<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://auth.qontak.com/3a5f72a37b5568090a0e676205211e80/oauth/token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "grant_type=password&username=trial@qontak.com&password=TrialUser%21&client_id=pQvygY1WlsymZQMTNMG2NTxwccUm5Ii3T9m5AG4Z8Lw&client_secret=x0IRTmWJYN94tgn_z455G6RrT12km_lLK6YMwR3fB1o",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/x-www-form-urlencoded",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

echo $response;

// result access_token => RdFd_cJIT8fITNynuFNCyVy2XEflC8wCt_bfoXToDK8

?>