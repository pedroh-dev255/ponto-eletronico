<?php
$url = "https://api.clinicadrhenriquefurtado.com.br/api/messages/send";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array(
  "Accept: application/json",
  "Authorization: Bearer 77f58ac9-7d38-40f8-a959-8a0b261602b8",
  "Content-Type: application/json",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = <<<DATA
        {
        "number":"$numero",
        "body":"$mensagem"  
        }
        DATA;
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($curl);
//echo $curl;
curl_close($curl);
//var_dump($resp);
//header('Location: .');
?>