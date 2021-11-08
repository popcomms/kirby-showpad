<?php

require_once 'inc/apps-db.php';
require_once 'inc/assets.php';
require_once 'inc/tags.php';

Kirby::plugin('popcomms/kirby-showpad', [
  'blueprints' => [
    'fields/showpad-apps-db' => __DIR__ . '/blueprints/fields/showpad-apps-db.yml',
    'fields/showpad-asset'   => __DIR__ . '/blueprints/fields/showpad-asset.yml',
    'fields/showpad-tags'    => __DIR__ . '/blueprints/fields/showpad-tags.yml'
  ],
  'fields' => [
    'showpad-asset' => [],
  ],
  'options' => [
    'domain'        => '',
    'mode'          => '',
    'client_id'     => '',
    'client_secret' => '',
    'username'      => '',
    'password'      => '',
    'redirect'      => '',
    'token'         => '',
  ],
  'api' => [
    'routes' => [

      // Fetch an Apps DB Store
      [
<<<<<<< Updated upstream
        'pattern' => 'showpad/appsdb/list',
        'method' => 'POST',
        'action' => function () {
          $kirby = kirby();
          $kirby->impersonate('kirby');

          $data = json_decode(file_get_contents('php://input'), true);
          $storeName = $data['data']['env'];

          function curl ($mode, $storeName, $url, $kirby, $token) {

            $kirby = kirby();
            $kirby->impersonate('kirby');

            if ($mode === 'GET') {
              $payload = null;
            } else {
              $data = array(
                'env' => $storeName
              );
              $payload = json_encode(array("data" => $data));
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $mode,
              CURLOPT_POSTFIELDS => $payload,
              CURLOPT_HTTPHEADER => array(
                $token,
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
          }

          $list = curl('GET',$storeName,'https://'.$kirby->option('popcomms.kirby-showpad.domain').'.showpad.biz/api/v3/appsdb/stores/'.$storeName.'/globals/entries', $kirby,  "Authorization: Bearer ".$kirby->option('popcomms.kirby-showpad.token'));

          $decodedData = json_decode($list, true);

          foreach ($decodedData as $array) {
            foreach ($array as $content) {
              $deletedStatus = curl('POST',$storeName,kirby()->site()->url().'/api/showpad/appsdb/empty/'.$content['id'], $kirby, "Authorization: Basic ".$kirby->option('popcomms.kirby-showpad.user'));
              var_dump ($deletedStatus);
            }
          }
          return 'success';
        }
      ],
      [
        'pattern' => 'showpad/appsdb/empty/(:all)',
        'method' => 'POST',
        'action' => function ($all) {
          $kirby = kirby();
          $kirby->impersonate('kirby');

          $data = json_decode(file_get_contents('php://input'), true);
          $storeName = $data['data']['env'];

          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://'.$kirby->option('popcomms.kirby-showpad.domain').'.showpad.biz/api/v3/appsdb/stores/'.$storeName.'/globals/entries/'.$all,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
              "Authorization: Bearer ".$kirby->option('popcomms.kirby-showpad.token'),
            ),
          ));

          $response = curl_exec($curl);

          curl_close($curl);
          echo $response;
          return 'Record Deleted with id of: '.$all;
        }
      ],
      [
        'pattern' => 'showpad/appsdb/insert',
        'method' => 'post',
        'action' => function () {

          $data = json_decode(file_get_contents('php://input'), true);
          $storeName = $data['data']['env'];

          $kirby = kirby();
          $kirby->impersonate('kirby');

          function parseString ($content, $storeName) {

            /*Run the utf converter here first to make sure it is the correct data type*/
            $decodedData = json_decode($content, true);

            $parsedData = utf8_converter($decodedData);

            $data = json_encode($parsedData);

            $splitString = str_split($data, $split_length = 242670);
            $counter = 1;
            $status = 'Failed';
            foreach ($splitString as $string) {
              $status = curl($string, $storeName, $counter, 'false');
              $counter++;
            }
            return $status;
          }

          function utf8_converter($array) {
            // go through the array and encode quotes that break the JSON format
            array_walk_recursive($array, function(&$item, $key){
                if (strpos($item, 'pages/products') === false) {
                  $item = str_replace('\'', '&#39;', $item);
                } else {
                }
            });

            return $array;
          }

          function curl ($data, $storeName, $id, $bool) {

            $kirby = kirby();
            $kirby->impersonate('kirby');

            $success = 'Success';

            if ($bool === 'true') {
              $decodedData = json_decode($data, true);

              $parsedData = utf8_converter($decodedData);

              $data = json_encode($parsedData);
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://'.$kirby->option('popcomms.kirby-showpad.domain').'.showpad.biz/api/v3/appsdb/stores/'.$storeName.'/globals/entries/'.$id,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'PUT',
              CURLOPT_POSTFIELDS =>' {
                "value": "'.addslashes($data).'"
              }',
              CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$kirby->option('popcomms.kirby-showpad.token'),
                'Content-Type: application/json'
              ),
            ));

            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            if ($info['http_code'] > 200) {
              var_dump($response);
              $success = 'Fail';
            }
            curl_close($curl);
            return $success;
          }

          $context = stream_context_create(array(
              'http' => array(
                  'header'  => "Authorization: Basic ".$kirby->option('popcomms.kirby-showpad.user')
              )
          ));

          $data = file_get_contents(kirby()->site()->url().'/api/content', false, $context);

          if (strlen($data) > $kirby->option('appsdb.maxsize')) {
            var_dump('Content too big needs to be split');
            $result = parseString($data, $storeName);
          } else {
            var_dump('Content short enough to fit in one upload');
            $result = curl($data, $storeName, '1', 'true');
          }

          return 'files uploaded: '.$result;
        }
      ],
      [
        'pattern' => 'showpad/asset/(:all)',
        'action' => function ($all) {
          $kirby = kirby();
          $kirby->impersonate('kirby');
=======
        'pattern' => 'showpad/appsdb/(:any)',
        'method' => 'GET',
        'action' => function ($store) {
          AppsDB::getStore($store);
        }
      ],
>>>>>>> Stashed changes

      // Populate an Apps DB Store
      [
        'pattern' => 'showpad/appsdb/(:all)',
        'method' => 'POST',
        'action' => function ($store) {
          AppsDB::populateStore($store);
        }
      ],

      // Empty an Apps DB Store
      [
        'pattern' => 'showpad/appsdb/(:all)',
        'method' => 'DELETE',
        'action' => function ($store) {
          AppsDB::emptyStore($store);
        }
      ],

      // Fetch a single entry from Apps DB Store
      [
        'pattern' => 'showpad/appsdb/(:any)/(:any)',
        'method' => 'GET',
        'action' => function ($store, $id) {
          AppsDB::getEntry($store, $id);
        }
      ],

      // Insert a single entry into Apps DB Store
      [
        'pattern' => 'showpad/appsdb/(:any)/(:any)',
        'method' => 'POST|PUT|UPDATE',
        'action' => function ($store, $id) {
          AppsDB::insertEntry($store, $id);
        }
      ],

      // Delete a single entry from Apps DB Store
      [
        'pattern' => 'showpad/appsdb/(:any)/(:any)',
        'method' => 'DELETE',
        'action' => function ($store, $id) {
          AppsDB::deleteEntry($store, $id);
        }
      ],


      [
        'pattern' => 'showpad/asset/(:all)',
        'action' => function ($all) {
          return getAsset($all);
        }
      ],
      [
        'pattern' => 'showpad/asset-slug/(:all)',
        'action' => function ($all) {
          return getAssetBySlug($all);
        }
      ],
      [
        'pattern' => 'showpad/asset-external-id/(:all)',
        'action' => function ($all) {
          return getAssetByExternalId($all);
        }
      ],
      [
        'pattern' => 'showpad/asset-save',
        'action' => function () {
          saveAsset();
        }
      ]
    ]
  ],
  'routes' => [
    [
      'pattern' => 'showpad/tags',
      'method' => 'GET',
      'action' => function () {
        return getTags();
      }
    ],
  ]
]);
