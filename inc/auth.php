<?php

function getToken () {
  $kirby = kirby();
  $kirby->impersonate('kirby');

  if ($kirby->option('popcomms.kirby-showpad.mode') === 'personal') {
    $token = $kirby->option('popcomms.kirby-showpad.token');
  } else if ($kirby->option('popcomms.kirby-showpad.mode') === 'oauth') {
    $token = auth();
  }
  return $token;
}

function isExpiredToken () {
  $now = time();
  $expires = $_SESSION['access_token_expires'];
  if ($now > $expires) {
    return true;
  } else {
    return false;
  }
}

function auth () {
  $kirby = kirby();
  $kirby->impersonate('kirby');

  if (!isset($_SESSION['access_token']) || isExpiredToken()) {
    $curl = curl_init();
    $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/oauth2/token';

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array(
        'grant_type'    => 'password',
        'username'      => $kirby->option('popcomms.kirby-showpad.username'),
        'password'      => $kirby->option('popcomms.kirby-showpad.password'),
        'client_id'     => $kirby->option('popcomms.kirby-showpad.client_id'),
        'client_secret' => $kirby->option('popcomms.kirby-showpad.client_secret')
      )
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response);

    $_SESSION['access_token'] = $data->access_token;
    $_SESSION['access_token_expires'] = time() + (int)$data->expires_in;

    return $data->access_token;
  } else {
    return $_SESSION['access_token'];
  }
}
