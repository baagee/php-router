<?php
// time:2020-04-29 04:54:34
return array (
  'static' => 
  array (
    'GET' => 
    array (
      '/articles' => '55f3c00d15b023045fba87fa1f95898d',
      '/article/list' => '55f3c00d15b023045fba87fa1f95898d',
      '/' => '55f3c00d15b023045fba87fa1f95898d',
      '/car' => 'e489eccc92bb28cc93b4288d93188ee2',
    ),
    'POST' => 
    array (
      '/article' => '3ff398bc17f1e10091bed0ea9c6703d8',
      '/car' => '58c7fc11e5ece9bd18a59e3d203905cc',
    ),
    'PUT' => 
    array (
      '/article' => '8d44bbe066fd97930ca58e194c405bc5',
      '/car' => '6d27977273248db8f4be3ba9cc56df2f',
    ),
  ),
  'regexp' => 
  array (
    'GET' => 
    array (
      'a' => 
      array (
        '\\/article\\/(?<article_id>\\S+?)' => 'b4f5685e3f278fc7568e8e49c25495ea',
      ),
      'c' => 
      array (
        '\\/car\\/(?<car_id>\\S+?)' => '4ff287cd2d7ccbc7c711d5c14d4cb276',
        '\\/car_info\\/(?<car_id>\\S+?)' => '4ff287cd2d7ccbc7c711d5c14d4cb276',
      ),
    ),
    'DELETE' => 
    array (
      'a' => 
      array (
        '\\/article\\/(?<article_id>\\S+?)' => 'dd04cec6145db49ba49299f4b4f56bf2',
      ),
      'c' => 
      array (
        '\\/car\\/(?<car_id>\\S+?)' => 'aff05646b57a90ad14cac01917d5c23d',
      ),
    ),
  ),
  'entry' => 
  array (
    '55f3c00d15b023045fba87fa1f95898d' => 
    array (
      0 => 
      array (
        0 => 'GET',
      ),
      1 => 'Article@list',
      2 => 
      array (
      ),
    ),
    'b4f5685e3f278fc7568e8e49c25495ea' => 
    array (
      0 => 
      array (
        0 => 'GET',
      ),
      1 => 'Article@detail',
      2 => 
      array (
      ),
    ),
    '3ff398bc17f1e10091bed0ea9c6703d8' => 
    array (
      0 => 
      array (
        0 => 'POST',
      ),
      1 => 'Article@add',
      2 => 
      array (
      ),
    ),
    '8d44bbe066fd97930ca58e194c405bc5' => 
    array (
      0 => 
      array (
        0 => 'PUT',
      ),
      1 => 'Article@update',
      2 => 
      array (
      ),
    ),
    'dd04cec6145db49ba49299f4b4f56bf2' => 
    array (
      0 => 
      array (
        0 => 'DELETE',
      ),
      1 => 'Article@delete',
      2 => 
      array (
      ),
    ),
    'e489eccc92bb28cc93b4288d93188ee2' => 
    array (
      0 => 
      array (
        0 => 'GET',
      ),
      1 => 'Car@list',
      2 => 
      array (
      ),
    ),
    '4ff287cd2d7ccbc7c711d5c14d4cb276' => 
    array (
      0 => 
      array (
        0 => 'GET',
      ),
      1 => 'Car@detail',
      2 => 
      array (
      ),
    ),
    '58c7fc11e5ece9bd18a59e3d203905cc' => 
    array (
      0 => 
      array (
        0 => 'POST',
      ),
      1 => 'Car@add',
      2 => 
      array (
      ),
    ),
    '6d27977273248db8f4be3ba9cc56df2f' => 
    array (
      0 => 
      array (
        0 => 'PUT',
      ),
      1 => 'Car@update',
      2 => 
      array (
      ),
    ),
    'aff05646b57a90ad14cac01917d5c23d' => 
    array (
      0 => 
      array (
        0 => 'DELETE',
      ),
      1 => 'Car@delete',
      2 => 
      array (
      ),
    ),
  ),
);