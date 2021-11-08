<?php

require_once 'auth.php';

function getTags () {

  $kirby = kirby();
  $kirby->impersonate('kirby');

  $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/tags.json?limit=1000&fields=name';

  $token = getToken();

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Accept: application/json',
      'Authorization: Bearer ' . $token
    ),
  ));

  $response = curl_exec($curl);
  curl_close($curl);

  $object = json_decode($response);
  return $object->response->items;
}
