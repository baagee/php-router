<?php
// time:2020-04-28 03:12:36
return array(
    'static' =>
        array(
            '/get/test1' => '24c0b17da24f7233632d199b98b9cae9',
            '/aget/test2' => 'b3a5b1ad2711ffa91b3d1f121f99b2af',
        ),
    'regexp' =>
        array(
            'GET' =>
                array(
                    'b' =>
                        array(
                            '\\/bget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                    'd' =>
                        array(
                            '\\/dget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                    'f' =>
                        array(
                            '\\/fget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                    'h' =>
                        array(
                            '\\/hget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                    '/' =>
                        array(
                            '\\/(?:(?<aaa>\\S+?))?' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                    'g' =>
                        array(
                            '\\/ggpp(?:\\/(?<aaa>\\S+?))?' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                ),
            'POST' =>
                array(
                    'a' =>
                        array(
                            '\\/aget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                    'g' =>
                        array(
                            '\\/gget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                            '\\/ggpp(?:\\/(?<aaa>\\S+?))?' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                ),
            'PUT' =>
                array(
                    'c' =>
                        array(
                            '\\/cget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                ),
            'DELETE' =>
                array(
                    'e' =>
                        array(
                            '\\/eget\\/(?<name>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                    '/' =>
                        array(
                            '\\/(?<id>\\S+?)' => '10e9fca8db24f6f027d2412ea3b6b248',
                        ),
                ),
        ),
    'entry' =>
        array(
            '24c0b17da24f7233632d199b98b9cae9' =>
                array(
                    0 =>
                        array(
                            0 => 'GET',
                        ),
                    1 => 'App@test1',
                    2 =>
                        array(),
                ),
            'b3a5b1ad2711ffa91b3d1f121f99b2af' =>
                array(
                    0 =>
                        array(
                            0 => 'GET',
                        ),
                    1 => 'App@test2',
                    2 =>
                        array(),
                ),
            '10e9fca8db24f6f027d2412ea3b6b248' =>
                array(
                    0 =>
                        array(
                            0 => 'GET',
                            1 => 'POST',
                            2 => 'DELETE',
                            3 => 'PUT',
                        ),
                    1 => 'App@test3',
                    2 =>
                        array(),
                ),
        ),
);