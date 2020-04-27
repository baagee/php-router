<?php
// time:2020-04-27 16:28:16
return array(
    'static' =>
        array(
            '/get/test1' =>
                array(
                    0 =>
                        array(
                            0 => 'GET',
                        ),
                    1 => 'App@test1',
                    2 =>
                        array(),
                ),
            '/aget/test2' =>
                array(
                    0 =>
                        array(
                            0 => 'GET',
                        ),
                    1 => 'App@test2',
                    2 =>
                        array(),
                ),
        ),
    'regexp' =>
        array(
            'GET' =>
                array(
                    'b' =>
                        array(
                            '\\/bget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'GET',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                    'd' =>
                        array(
                            '\\/dget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'GET',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                    'f' =>
                        array(
                            '\\/fget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'GET',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                    'h' =>
                        array(
                            '\\/hget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'GET',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                    '/' =>
                        array(
                            '\\/(?:(?<aaa>\\S+?))?' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'GET',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                    'g' =>
                        array(
                            '\\/ggpp(?:\\/(?<aaa>\\S+?))?' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'GET',
                                            1 => 'POST',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                ),
            'POST' =>
                array(
                    'a' =>
                        array(
                            '\\/aget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'POST',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                    'g' =>
                        array(
                            '\\/gget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'POST',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                            '\\/ggpp(?:\\/(?<aaa>\\S+?))?' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'GET',
                                            1 => 'POST',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                ),
            'PUT' =>
                array(
                    'c' =>
                        array(
                            '\\/cget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'PUT',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                ),
            'DELETE' =>
                array(
                    'e' =>
                        array(
                            '\\/eget\\/(?<name>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'DELETE',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                    '/' =>
                        array(
                            '\\/(?<id>\\S+?)' =>
                                array(
                                    0 =>
                                        array(
                                            0 => 'DELETE',
                                        ),
                                    1 => 'App@test3',
                                    2 =>
                                        array(),
                                ),
                        ),
                ),
        ),
);