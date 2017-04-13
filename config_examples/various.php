<?php

/*
 * This file is part of the HolidayCalendar, see:
 * Github: https://github.com/hausl/HolidayCalendar
 *
*/


/*
 * --------------------------
 * config file custom events
 * --------------------------
 *
 * Ereignistage (event days)
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
                'de' => 'Ostersonntag'
            ],
            'date'   => 'E',
            'filter' => []
        ],

        [
            'names'  => [
                'de' => 'Beginn Sommerzeit',
                'en' => 'Start of summertime'
            ],
            'date'   => 'last sunday of march',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Muttertag'],
            'date'   => 'second sunday of may',
            'filter' => []
        ],

        [
            'names'  => [
                'de' => 'Ende Sommerzeit',
                'en' => 'End of summertime'
             ],
            'date'   => 'last sunday of october',
            'filter' => []
        ],

        [
            'names'  => ['de' => '4. Advent'],
            'date'   => '11/26, next Sunday, +3 weeks',
            'filter' => []
        ]

    ]

];
