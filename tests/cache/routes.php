<?php
// time:2019-04-10 05:53:01
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
            '/get/test2' =>
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
            'g' =>
                array(
                    '\\/get\\/(?<name>\\S+?)' =>
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
        ),
);