<?php
Kirby::plugin('popcomms/kirby-showpad', [
  'fields' => [
    'showpad-asset' => [],
    'showpad-tags' => [],
    'showpad-tags-mode' => []
  ],
  'options' => [
    'domain' => '',
    'token'  => ''
  ],
  'api' => [
    'routes' => [
      [
        'pattern' => '/showpad/asset/(:all)',
        'action' => function ($all) {
          $kirby = kirby();
          $kirby->impersonate('kirby');

          $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/assets/'.$all.'.json';

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
              'Authorization: Bearer ' . $kirby->option('popcomms.kirby-showpad.token')
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          return $response;
        }
      ],
      [
        'pattern' => '/showpad/asset-slug/(:all)',
        'action' => function ($all) {
          $kirby = kirby();
          $kirby->impersonate('kirby');

          $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/assets.json?slug='.$all;

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
              'Authorization: Bearer ' . $kirby->option('popcomms.kirby-showpad.token')
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          return $response;
        }
      ],
      [
        'pattern' => '/showpad/asset-external-id/(:all)',
        'action' => function ($all) {
          $kirby = kirby();
          $kirby->impersonate('kirby');

          $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/assets.json?externalId='.$all;

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
              'Authorization: Bearer ' . $kirby->option('popcomms.kirby-showpad.token')
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          return $response;
        }
      ],
      [
        'pattern' => '/showpad/tags',
        'action' => function () {
          $kirby = kirby();
          $kirby->impersonate('kirby');

          $url = 'https://' . $kirby->option('popcomms.kirby-showpad.domain') . '.showpad.biz/api/v3/tags.json?limit=1000&fields=name';

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
              'Authorization: Bearer ' . $kirby->option('popcomms.kirby-showpad.token')
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          return $response;
        }
      ],
    ]
  ]
]);
