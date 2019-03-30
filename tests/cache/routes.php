<?php
// time:2019-03-30 16:04:13
return array (
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
);