<?php


return [

    /*
     * To identify the config-entries, from which config-file they come.
     * Overwrite this value, if you need.
    */
    basename(__FILE__),

   /*
    * config data
    */
    [

        [
            'names'  => [
                'de' => 'Test T+2 DE',
                'en' => 'test T+2 EN'
            ],
            'date'   => 'T+2',
            'filter' => []
        ],

        [
            'names'  => [
                'de' => 'Test E'
            ],
            'date'   => 'E',
            'filter' => []
        ],

        [
            'names'  => [
                'de' => 'Test E+5'
            ],
            'date'   => 'E+5',
            'filter' => []
        ],

        [
            'names'  => [
                'de' => 'Test E-5'
            ],
            'date'   => 'E-5',
            'filter' => []
        ]

    ]

];
