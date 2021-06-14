<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://service.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"to_number\":\"6282146773310\",\"to_name\":\"Rivan\",\"message_template_id\":\"422931b5-8fd9-4e67-a9f4-24ecc15626fb\",\"channel_integration_id\":\"22a75949-446a-4267-8ac2-1f105441d8fc\",\"language\":{\"code\":\"id\"},\"parameters\":{\"body\":[{\"key\":\"1\",\"value\":\"full_name\",\"value_text\":\"Wahyu Aditya\"}, {\"key\":\"2\",\"value\":\"count_late\",\"value_text\":\"1\"}]}}",
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer RdFd_cJIT8fITNynuFNCyVy2XEflC8wCt_bfoXToDK8",
    "Content-Type: application/json"
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

?>