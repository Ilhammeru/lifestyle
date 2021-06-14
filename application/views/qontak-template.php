<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://service.qontak.com/api/open/v1/templates/whatsapp",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer RdFd_cJIT8fITNynuFNCyVy2XEflC8wCt_bfoXToDK8"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

// response template_id => 3a169490-5e1c-4ecc-a15e-1059ce6f74a5

?>