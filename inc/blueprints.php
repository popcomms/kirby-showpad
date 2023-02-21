<?php

$blueprints = [

  'pages/media-library' => function ($kirby) {
    return [
      'title' => 'Media Library',
      'sections' => [
        'files' => [
          'type'     => 'files',
          'limit'    => 100
        ]
      ]
    ];
  }

];
