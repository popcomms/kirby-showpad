<?php
Kirby::plugin('popcomms/kirby-showpad', [
  'fields' => [
    'showpad-asset' => [],
  ],
  'options' => [
    'domain' => '',
    'token'  => ''
  ],
  'api' => [
    'routes' => [
      [
        'pattern' => 'showpad/asset/(:all)',
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
        'pattern' => 'showpad/asset-slug/(:all)',
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
        'pattern' => 'showpad/asset-external-id/(:all)',
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
        'pattern' => 'showpad/asset-save',
        'action' => function () {
          $kirby = kirby();
          $kirby->impersonate('kirby');

          $url = get('u');
          $field = get('f');
          $name = str_replace('/', '+', get('n'));
          $page = $kirby->site()->find(get('p'));
          $file = $page->root().'/'.$name;

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

          F::write($file, $response, false);

          try {
            $page->update([
              'showpad_asset_preview' => $name
            ]);
            return [
              'response'  => $file,
              'blueprint' => $page->blueprint()->title()
            ];
          } catch(Exception $e) {
            echo $e->getMessage();
          }
        }
      ]
    ]
  ],
  'routes' => [
    [
      'pattern' => 'showpad/tags',
      'method' => 'GET',
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
        $object = json_decode($response);
        return $object->response->items;
      }
    ],
  ],
  'snippets' => [

  ],
  'blueprints' => [
    'fields/showpad-asset' => __DIR__ . '/blueprints/fields/showpad-asset.yml',
    'fields/showpad-tags'  => __DIR__ . '/blueprints/fields/showpad-tags.yml'
  ]
]);
