<?php

/*
 * This file is part of the HolidayCalendar, see:
 * Github: https://github.com/hausl/HolidayCalendar
 *
*/


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
                'de' => 'Test eins, heute' // absichtlich hier kein "en"!
            ],
            'date'   => date('m').'-'.date('d'),
            'filter' => []
        ],
        [
            'names'  => [
                'de' => 'Test zweit, auch heute'
            ],
            'date'   => date('m').'-'.date('d'),
            'filter' => []
        ]

    ]

];
