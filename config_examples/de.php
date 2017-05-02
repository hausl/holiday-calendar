<?php

/*
 * This file is part of the HolidayCalendar, see:
 * Github: https://github.com/hausl/holiday-calendar
 *
*/


/*
---------------------
config file Germany
---------------------

http://www.feiertage-deutschland.de/

NATIONAL (9)

    01.01.  Neujahr
    E-2     Karfreitag
    E+1     Ostermonatg
    01.05.  Tag der Arbeit
    E+39    Christi Himmelfahrt
    E+50    Pfingsmontag
    03.10.  Tag der Deutschen Einheit
    25.12.  1. Weihnachtstag
    26.12.  2. Weihnachtstag


REGIONAL (6)

    06.01.  Heilige Drei Könige     BW BY ST
    E+60    Fronleichnahm           BW BY HE NW RP SL SN TH
    15.08.  Mariä Himmelfahrt       BY SL
    31.10.  Reformationstag         BW BB MV SN ST TH
    01.11.  Allerheiligen           BW BY NW RP SL
    16.11.  Buß- und Bettag         SN


REGIONEN (16)

    BW Baden-Württemberg
    BY Bayern
    BE Berlin
    BB Brandenburg
    HB Freie Hansestadt Bremen
    HH Hamburg
    HE Hessen
    MV Mecklenburg-Vorpommern
    NI Niedersachsen
    NW Nordrhein-Westfalen
    RP Rheinland-Pfalz
    SL Saarland
    SN Sachsen
    ST Sachsen-Anhalt
    SH Schleswig-Holstein
    TH Thüringen

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
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Karfreitag'],
            'date'   => 'E-2',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Ostermontag'],
            'date'   => 'E+1',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Tag der Arbeit'],
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
            'names'  => ['de' => 'Tag der Deutschen Einheit'],
            'date'   => '10-03',
            'filter' => []
        ],

        [
            'names'  => ['de' => '1. Weihnachtstag'],
            'date'   => '12-25',
            'filter' => []
        ],

        [
            'names'  => ['de' => '2. Weihnachtstag'],
            'date'   => '12-26',
            'filter' => []
        ],

        [
            'names'  => ['de' => 'Heilige Drei Könige'],
            'date'   => '01-06',
            'filter' => ['BW', 'BY', 'ST']
        ],

        [
            'names'  => ['de' => 'Fronleichnahm'],
            'date'   => 'E+60',
            'filter' => ['BW', 'BY', 'HE', 'NW', 'RP', 'SL', 'SN', 'TH']
        ],

        [
            'names'  => ['de' => 'Mariä Himmelfahrt'],
            'date'   => '08-15',
            'filter' => ['BY', 'SL']
        ],

        [
            'names'  => ['de' => 'Reformationstag'],
            'date'   => '10-31',
            'filter' => ['BW', 'BB', 'MV', 'SN', 'ST', 'TH']
        ],

        [
            // https://de.wikipedia.org/wiki/Reformationstag#Deutschland
            'names'  => ['de' => 'Reformationstag 2017'],
            'date'   => '2017-10-31',
            'filter' => ['BY','BE','HB','HH','HE','NI','NW','RP','SL','SH']
        ],

        [
            'names'  => ['de' => 'Allerheiligen'],
            'date'   => '11-01',
            'filter' => ['BW', 'BY', 'NW', 'RP', 'SL']
        ],

        [
            'names'  => ['de' => 'Buß- und Bettag'],
            'date'   => '11/23, last Wed',
            'filter' => ['SN']
        ]
    ]

];
