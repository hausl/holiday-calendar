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
                'de' => 'Test gestern DE',
                'en' => 'test yesterday DE'
            ],
            'date'   => 'yesterday',
            'filter' => []
        ]

    ]

];
