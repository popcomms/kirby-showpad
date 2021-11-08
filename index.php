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
        'pattern' => 'showpad/appsdb/(:any)',
        'method' => 'GET',
        'action' => function ($store) {
          AppsDB::getStore($store);
        }
      ],

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
