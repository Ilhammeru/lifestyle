<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://service.qontak.com/api/open/v1/integrations?target_channel=wa",
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

// result organization_id => a6642f0c-d865-471f-97c7-00adaa313d29
// result authorization => YWRtaW46UW9udGFrVGVzdDMwQXByaWwh
// result chanel integration => 22a75949-446a-4267-8ac2-1f105441d8fc

?>

