<?php

require 'inc/api.php';
require 'inc/areas.php';
require 'inc/blueprints.php';
require 'inc/options.php';

Kirby::plugin('popcomms/kirby-showpad', [
  'api'        => $api,
  'areas'      => $areas,
  'blueprints' => $blueprints,
  'options'    => $options,
]);
