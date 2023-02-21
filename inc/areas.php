<?php

$areas = [
  'media_library' => function ($kirby) {
    return [
      'dialogs' => [
        'media_library/add' => [
          'load' => function () {
            return [
              'component' => 'k-form-dialog',
              'props'     => [
                'fields' => [
                  // Showpad App Link
                ],
              ]
            ];
          },
          'submit' => function () {
            //  Add functionality
          }
        ]
      ]
    ];
  }
];
