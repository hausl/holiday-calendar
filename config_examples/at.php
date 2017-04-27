<?php

/*
 * This file is part of the HolidayCalendar, see:
 * Github: https://github.com/hausl/holiday-calendar
 *
*/


/*
---------------------
config file Austria
---------------------

http://www.feiertage-oesterreich.at/

NATIONAL (13):

    01.01.  Neujahr
    06.01.  Hl. drei Könige
    E+1     Ostermontag
    01.05.  Staatsfeiertag
    E+39    Christi Himmelfahrt
    E+50    Pfingstmontag
    E+60    Fronleichnam
    15.08.  Mariä Himmelfahrt
    26.10.  Nationalfeiertag
    01.11.  Allerheiligen
    08.12.  Mariä Empfängnis
    25.12.  Christtag
    26.12.  Stefanitag

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
            'names'  => ['de' => 'Neujahr'],
            'date'   => '01-01',
            'filter' => [],
        ],

        [
            'names'  => ['de' => 'Heilige drei Könige'],
            'date'   => '01-06',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Ostermontag'],
            'date'   => 'E+1',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Staatsfeiertag'],
            'date'   => '05-01',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Christi Himmelfahrt'],
            'date'   => 'E+39',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Pfingsmontag'],
            'date'   => 'E+50',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Fronleichnam'],
            'date'   => 'E+60',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Mariä Himmelfahrt'],
            'date'   => '08-15',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Nationalfeiertag'],
            'date'   => '10-26',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Allerheiligen'],
            'date'   => '11-01',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Mariä Empfängnis'],
            'date'   => '12-08',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Christtag'],
            'date'   => '12-25',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Stefanitag'],
            'date'   => '12-26',
            'filter' => []
        ]
    ]

];
