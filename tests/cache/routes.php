<?php
// time:2019-04-08 08:30:16
return array (
  'static' => 
  array (
    '/get/test1' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
      ),
      'callback' => 'App@test1',
      'other' => 
      array (
      ),
    ),
    '/get/test2' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
      ),
      'callback' => 'App@test2',
      'other' => 
      array (
      ),
    ),
  ),
  'regexp' => 
  array (
    'g' => 
    array (
      '\\/get\\/(?<name>\\S+?)' => 
      array (
        'methods' => 
        array (
          0 => 'GET',
        ),
        'callback' => 'App@test3',
        'other' => 
        array (
        ),
      ),
    ),
    '/' => 
    array (
      '\\/(?:(?<aaa>\\S+?))?' => 
      array (
        'methods' => 
        array (
          0 => 'GET',
        ),
        'callback' => 'App@test3',
        'other' => 
        array (
        ),
      ),
    ),
  ),
);