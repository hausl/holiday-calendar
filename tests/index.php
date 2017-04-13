<?php

error_reporting(-1);
ini_set('display_errors', true);

require __DIR__."/../src/HolidayCalendar.php";


function check($result, $expected, $line) {
    echo "Line $line: ";

    if ($result === $expected) {
        echo '<b style="color:green">OK</b> <br>Result: ';
        var_dump($result);
    } else {
        echo '<b style="color:red">Fehler</b> <br>Result: ';
        var_dump($result);
        echo '<br>Expected: ';
        var_dump($expected);
    }

    echo "\n<hr>\n";
}

$tpl = '<h3>%s</h3>';

$configPath     = __DIR__.'/../config_examples/';
$testconfigPath = __DIR__.'/';


// -----------------------------------------------------------------------------
// Instaniciation
// -----------------------------------------------------------------------------
printf($tpl, 'Instaniciation');

$holiday = new HolidayCalendar('de.php', $configPath);
check( ($holiday instanceof HolidayCalendar), true, __LINE__ );

$holiday = new HolidayCalendar( ['de.php', 'at.php'], $configPath);
check( ($holiday instanceof HolidayCalendar), true, __LINE__ );

$holiday = new HolidayCalendar( ['de.php'], $configPath);
check( ($holiday instanceof HolidayCalendar), true, __LINE__ );

$holiday = new HolidayCalendar('de.php', $configPath);
$holiday->setLang('en');
check( ($holiday instanceof HolidayCalendar), true, __LINE__ );

$holiday = new HolidayCalendar('de.php', $configPath);
check( ($holiday instanceof HolidayCalendar), true, __LINE__ );

$holiday = new HolidayCalendar( ['de.php', 'at.php'] , $configPath );
check( ($holiday instanceof HolidayCalendar), true, __LINE__ );



// -----------------------------------------------------------------------------
// Lang-handling
// -----------------------------------------------------------------------------
printf($tpl, 'Lang-handling');

$holiday = new HolidayCalendar('de.php', $configPath);
check( $holiday->getLang(), 'de', __LINE__ );
check( $holiday->getFallbackLang(), 'de', __LINE__ );

$holiday->setLang('en');
$holiday->setFallbackLang('en');
check( $holiday->getLang(), 'en', __LINE__ );
check( $holiday->getFallbackLang(), 'en', __LINE__ );

$holiday->setLang('de')->setFallbackLang('de');  // method chain
check( $holiday->getLang(), 'de', __LINE__ );
check( $holiday->getFallbackLang(), 'de', __LINE__ );



// -----------------------------------------------------------------------------
// isHoliday()
// -----------------------------------------------------------------------------
printf($tpl, 'isHoliday()');


$a = ['', ' ', 'now', 'today', NULL, false];


// we toggle a holiday today - all should be true
$holiday = new HolidayCalendar('test_config_1.php', $testconfigPath);

check( $holiday->isHoliday(), true, __LINE__ );
foreach ($a as $val) {
    check( $holiday->isHoliday($val), true, __LINE__ );
}


// today is no holiday - all should be false
$holiday = new HolidayCalendar('test_config_3.php', $testconfigPath);

check( $holiday->isHoliday(), false, __LINE__ );
foreach ($a as $val) {
    check( $holiday->isHoliday($val), false, __LINE__ );
}



// -----------------------------------------------------------------------------
// findHolidayByName()
// -----------------------------------------------------------------------------
printf($tpl, 'findHolidayByName()');

$holiday = new HolidayCalendar('de.php', $configPath);
check( count($holiday->findHolidayByName('freitag', 2017)), 1, __LINE__ );
check( count($holiday->findHolidayByName('montag', 2017)), 2, __LINE__ );
check( count($holiday->findHolidayByName('foo', 2017)), 0, __LINE__ );

$holiday = new HolidayCalendar('test_config_2.php', $testconfigPath);
$holiday->setLang('en');
check( count($holiday->findHolidayByName('test', 2017)), 2, __LINE__ );



// -----------------------------------------------------------------------------
// findHolidayByDate()
// -----------------------------------------------------------------------------
printf($tpl, 'findHolidayByDate()');

$holiday = new HolidayCalendar('de.php', $configPath);
check( count($holiday->findHolidayByDate('2017-10-15')), 0, __LINE__ );
check( count($holiday->findHolidayByDate('2017-01-01')), 1, __LINE__ );
check( count($holiday->findHolidayByDate('')), 0, __LINE__ );

$holiday = new HolidayCalendar('test_config_1.php', $testconfigPath); // no lang
check( count($holiday->findHolidayByDate('')), 2, __LINE__ );

$holiday = new HolidayCalendar('test_config_1.php', $testconfigPath); // lang en
$holiday->setLang('en');
check( $holiday->findHolidayByDate('')[0]['name'], 'Test eins, heute', __LINE__ ); // fallback lang
check( $holiday->findHolidayByDate('')[1]['name'], 'Test zweit, auch heute', __LINE__ );



// -----------------------------------------------------------------------------
// findHolidayByDate() via DateTime object Input
// -----------------------------------------------------------------------------
printf($tpl, 'findHolidayByDate() via DateTime object Input');

$holiday = new HolidayCalendar('de.php', $configPath);

$dt = new DateTime('2017-10-15');
check( count($holiday->findHolidayByDate($dt)), 0, __LINE__ );

$dt = new DateTime('2017-01-01');
check( count($holiday->findHolidayByDate($dt)), 1, __LINE__ );



// -----------------------------------------------------------------------------
// findHolidayByDate() via DateTimeImmutable object Input
// -----------------------------------------------------------------------------
printf($tpl, 'findHolidayByDate() via DateTimeImmutable object Input');

$holiday = new HolidayCalendar('de.php', $configPath);

$dt = new DateTimeImmutable('2017-10-15');
check( count($holiday->findHolidayByDate($dt)), 0, __LINE__ );

$dt = new DateTimeImmutable('2017-01-01');
check( count($holiday->findHolidayByDate($dt)), 1, __LINE__ );



// -----------------------------------------------------------------------------
// createHolidaysList()
// -----------------------------------------------------------------------------
printf($tpl, 'createHolidaysList()');

$holiday = new HolidayCalendar('de.php', $configPath);
$aHolidayList = $holiday->createHolidaysList(2017);
check( count($aHolidayList), 15, __LINE__ );

$holiday = new HolidayCalendar('test_config_1.php', $testconfigPath);
$holiday->setLang('en');
$aHolidayList = $holiday->createHolidaysList(2017);
check( count($aHolidayList), 2, __LINE__ );



// -----------------------------------------------------------------------------
// createHolidaysList() - Dates "at.php" Config
// -----------------------------------------------------------------------------
printf($tpl, 'createHolidaysList() - Dates "at.php" config');

$holiday = new HolidayCalendar('at.php', $configPath);
$aHolidayList = $holiday->createHolidaysList(2017);
$jsonNeu = json_encode($aHolidayList, JSON_UNESCAPED_UNICODE);

$jsonSaved = <<<JSON
[{"date":"2017-01-01","name":"Neujahr","config":"at.php","filter":[]},{"date":"2017-01-06","name":"Heilige drei Könige","config":"at.php","filter":[]},{"date":"2017-04-17","name":"Ostermontag","config":"at.php","filter":[]},{"date":"2017-05-01","name":"Staatsfeiertag","config":"at.php","filter":[]},{"date":"2017-05-25","name":"Christi Himmelfahrt","config":"at.php","filter":[]},{"date":"2017-06-05","name":"Pfingsmontag","config":"at.php","filter":[]},{"date":"2017-06-15","name":"Fronleichnam","config":"at.php","filter":[]},{"date":"2017-08-15","name":"Mariä Himmelfahrt","config":"at.php","filter":[]},{"date":"2017-10-26","name":"Nationalfeiertag","config":"at.php","filter":[]},{"date":"2017-11-01","name":"Allerheiligen","config":"at.php","filter":[]},{"date":"2017-12-08","name":"Mariä Empfängnis","config":"at.php","filter":[]},{"date":"2017-12-25","name":"Christtag","config":"at.php","filter":[]},{"date":"2017-12-26","name":"Stefanitag","config":"at.php","filter":[]}]
JSON;

check( $jsonNeu, $jsonSaved,  __LINE__ );


// -----------------------------------------------------------------------------
// createHolidaysList() - Dates "various.php" Config
// -----------------------------------------------------------------------------
printf($tpl, 'createHolidaysList() - Dates "various.php" config');

$holiday = new HolidayCalendar('various.php', $configPath);
$aHolidayList = $holiday->createHolidaysList(2017);
$jsonNeu = json_encode($aHolidayList, JSON_UNESCAPED_UNICODE);

$jsonSaved = <<<JSON
[{"date":"2017-03-26","name":"Beginn Sommerzeit","config":"various.php","filter":[]},{"date":"2017-04-16","name":"Ostersonntag","config":"various.php","filter":[]},{"date":"2017-05-14","name":"Muttertag","config":"various.php","filter":[]},{"date":"2017-10-29","name":"Ende Sommerzeit","config":"various.php","filter":[]},{"date":"2017-12-24","name":"4. Advent","config":"various.php","filter":[]}]
JSON;

check( $jsonNeu, $jsonSaved,  __LINE__ );



// -----------------------------------------------------------------------------
// createHolidaysList() - Dates unsorted
// -----------------------------------------------------------------------------
printf($tpl, 'createHolidaysList() - Dates unsorted');

$holiday = new HolidayCalendar( ['de.php', 'at.php', 'various.php'], $configPath);
$a1 = $holiday->createHolidaysList(2017);
$a2 = $holiday->createHolidaysList(2017, false);

check( $a1 == $a2, false,  __LINE__ );


// -----------------------------------------------------------------------------
// DateAnchor E und alternativer DateAnchor T+2
// -----------------------------------------------------------------------------
printf($tpl, 'DateAnchor E und alternativer DateAnchor T+2');

$holiday = new HolidayCalendar('test_config_4.php', $testconfigPath);
$holiday->setDateAnchor('T', '2017-02-01');  // in config: T+2
$aHolidayList = $holiday->createHolidaysList(2017);

check( $aHolidayList[0]['date'], '2017-02-03', __LINE__ );
check( count($aHolidayList), 4, __LINE__ );

check( $holiday->isHoliday('2017-04-11'), true, __LINE__ ); // E-5
check( $holiday->isHoliday('2017-04-16'), true, __LINE__ ); // E
check( $holiday->isHoliday('2017-04-21'), true, __LINE__ ); // E+5



// -----------------------------------------------------------------------------
// Various
// -----------------------------------------------------------------------------
printf($tpl, 'Various');

echo "<p>Memory: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB</p>\n\n";
echo "\n<hr>\n";


// -----------------------------------------------------------------------------
// Show this file
// -----------------------------------------------------------------------------
#printf($tpl, 'Show this PHP file');

#highlight_file(__FILE__);
