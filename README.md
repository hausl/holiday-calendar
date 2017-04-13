# HolidayCalendar (alpha)

This class provides functions for calculating holidays. A holiday is recorded once in the definitions (config files), the corresponding dates can autom. calculated, queried and outputted for the future or the past.

So, the functions of the class are based on the config files with the holiday definitions. Because i am Austrian **for Austria and Germany, the config files of the legal holidays are delivered with the class**. Furthermore, a small sample with various "event days" (no legal holidays) as example for the date definition syntax.

**Important!**<br>
You can use the files as they are, or as templates and build on them, as well as create new ones, just as you needs. **Just make sure, you put the productive config files outside of the `src` or `vendor` directory.** E.g. Composer will overwrite them on update.

## Usage

### Include class directly

``` php
// depending on your path, e.g.
require __DIR__ . '/holiday-calendar/src/HolidayCalendar.php';
```

### Or install via Composer

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/hausl/holiday-calendar"
    }
]
```

### Load config data

``` php
// load config data de.php (Germany)
$holiday = new HolidayCalendar('de.php', 'path_to_your_config_files');

// e.g.
$holiday = new HolidayCalendar('de.php', __DIR__.'/myConfigs');
```

``` php
// multiple files are specified as array
$holiday = new HolidayCalendar( ['de.php', 'examples.php'], 'path_to_your_config_files' );
```

If path is not set, current dir is used.


### Timezone (optional)

You can set the timezone. Otherwise the current PHP setting will be used.

``` php
$holiday->setDateTimezone( new DateTimeZone('Europe/Berlin') );
```

### Language (optional)

Default is `de`. Is used for the holiday names at output as stated in the config files. Also applies to the holiday name search.

``` php
$holiday->setLang('en');
```

If desired, the fallback language (`de`) can also be changed. This is used, if the language defined by setLang() does not exist for a holiday entry in the config file and the search. It is also `de` by default.

``` php
$holiday->setFallbackLang('de');
```

You can use `getLang()` and `getFallbackLang()` to query the currently set languages.


### Limit config data (optional)

If desired, the basis data for all subsequent queries/outputs can be limited.


#### Limit by filter

Filter tags are freely definable according to your requirements.

If filters are set in the application after instantiation, only entries containing the filters are considered in the output/query. Likewise, all entries that do not contain filters in the config file. Therefore see next section.

``` php
// only all data with filter 'BY' ("Bavaria") and all without filter definition
$holiday->setFilter( ['BY'] ); // always specify the filter as an Array
```

Extract from a config file, note the `filter` field, you can also find 'BY' there.

``` php
    [
        'names'  => [
            'de' => 'Heilige Drei Könige',
            'en' => 'Three holy kings'
        ],
        'date'   => '01-06',
        'filter' => ['BW', 'BY', 'ST']
    ],
```


#### Exclude entries without (with empty) filter definition

If `true`, no data is included in the result that has no (empty) filter definitions in the config file. Since this are usually the national holidays, one will rarely need this.

``` php
// exclude all with empty filter definition
$holiday->setIgnoreEmptyFilterConfigItems(true);
```


### Output/Query

**Remember! The basis for all queries is always the loaded config(s) and (if used) filters.**

Depending on the method you use, returned is either

a) a *bool* (`true`/`false`), <br>
b) or a multi-dimensional array with the data. If there is no data from the query, an empty array is returned.


#### Check whether a date is a holiday

``` php
var_dump( $holiday->isHoliday('2015-12-25') );
// true
```

To check whether **today** is a holiday, omit the date in the call.<br>
Alternatively you could use `''`, `'now'` oder `'today'`.

``` php
var_dump( $holiday->isHoliday() );
// false
```


#### Holiday name/infos by date

``` php
print_r( $holiday->findHolidayByDate('2015-12-25') );
/*
Array
(
    [0] => Array
        (
            [date] => 2015-12-25
            [name] => 1. Weihnachtstag
            [config] => de.php
            [filter] => Array
                (
                )
        )
)
*/
```

Alternatively, a DateTime[Immutable] object can be passed instead of a date string `'2015-12-25'`.

``` php
$dt = new DateTime('2017-10-15');
var_dump( $holiday->findHolidayByDate($dt) );

```

To check whether **today** is a holiday, omit the date in the call.<br>
Alternatively you could use `''`, `'now'` oder `'today'`.

Here for example for the 2017-03-20 returns an empty array, because no config entry matches.

``` php
print_r( $holiday->findHolidayByDate() );
/*
Array
(
)
*/
```


#### Search for a holiday by name

``` php
print_r( $holiday->findHolidayByName('Karfreitag', 2016) );
/*
Array
(
    [0] => Array
        (
            [date] => 2016-03-25
            [name] => Karfreitag
            [config] => de.php
            [filter] => Array
                (
                )
        )
)
*/
```

Depending on the search term, also more results could be available.

``` php
print_r( $holiday->findHolidayByName('montag', 2016) );
/*
Array
(
    [0] => Array
        (
            [date] => 2016-03-28
            [name] => Ostermontag
            [config] => de.php
            [filter] => Array
                (
                )
        )

    [1] => Array
        (
            [date] => 2016-05-16
            [name] => Pfingsmontag
            [config] => de.php
            [filter] => Array
                (
                )
        )
)
*/
```


#### Create a list

``` php
// Create holiday list 2016
$aHolidays = $holiday->createHolidaysList(2016);

print_r($aHolidays);
/*
Array
(
    [0] => Array
        (
            [date] => 2016-01-01
            [name] => Neujahr
            [config] => de.php
            [filter] => Array
                (
                )

        )

    [1] => Array
        (
            [date] => 2016-01-06
            [name] => Heilige Drei Könige
            [config] => de.php
            [filter] => Array
                (
                    [0] => BW
                    [1] => BY
                    [2] => ST
                )

        )

etc ...

*/
```

Added with filter 'BW' ("Baden-Württemberg") and a `foreach()` for 2017

``` php
$holiday = new HolidayCalendar('de.php');  // Germany
$holiday->setFilter(['BW']);  // all with 'BW' or empty filter definition

$aHolidays = $holiday->createHolidaysList(2017);

foreach ($aHolidays as $aHoliday) {
    printf('%s: %s<br>', $aHoliday['date'], $aHoliday['name']);
}

/*
2017-01-01: Neujahr
2017-01-06: Heilige Drei Könige
2017-04-14: Karfreitag
2017-04-17: Ostermontag
2017-05-01: Tag der Arbeit
2017-05-25: Christi Himmelfahrt
2017-06-05: Pfingsmontag
2017-06-15: Fronleichnahm
2017-10-03: Tag der Deutschen Einheit
2017-10-31: Reformationstag
2017-11-01: Allerheiligen
2017-12-25: 1. Weihnachtstag
2017-12-26: 2. Weihnachtstag
*/

```

#### Sort the output

By default, the array (holiday list) is sorted ascending by date, name. If this is not the case, you can specify `false` as the second parameter in createHolidaysList(). So you can e.g. sort it in any other way for your needs.

``` php
$aHolidays = $holiday->createHolidaysList(2017, false);
```


#### Shortcuts via Method-Chaining

The above given setter methods `setDateTimezone()`, `setFilter()`, and `setIgnoreEmptyFilterConfigItems()` can also be used in a shortened form via method chaining. This also applies to `setLang()` and `setFallbackLang()` methods.

``` php
$aData = $holiday->setFilter(['BY'])->setIgnoreEmptyFilterConfigItems(true)->createHolidaysList(2016);
```


## Config files

The config files return a PHP array with the definitions of the holidays. Different date expressions are available for definition of a holiday.

``` php
[
    'names'  => [                       // Holiday name, language dependent
        'de' => 'Heilige Drei Könige',
        'en' => 'Three holy kings'
    ],
    'date'   => '01-06',                // Date expression, here: Fix datum of the Year (m-d)
    'filter' => ['BW', 'BY', 'ST']      // Filter array, here "Baden-Württemberg", "Bayern", "Sachsen-Anhalt"
],
```

### Date expressions

Here are some examples of possible variants.


#### Unique date

A unique holiday, only this year. Is specified in the format `Y-m-d`

`2016-10-15`

#### Fixed date per year

Repeats annually. Specified in the format `m-d`.

`01-01` New Year's Day<br>
`12-25` Christmas Day

#### Easter Sunday

Easter Sunday is represented by a simple `E`. This date anchor is fixed and can not be changed. The determination of the date takes place in the class.

#### Easter Relative Days

Pentecost day: `E+50` (Easter Sunday + 50 days)<br>
Good Friday: `E-2` (Easter Sunday - 2 days)

#### Relative date expressions

Start summer time (DST): `'last sunday of march'`<br>
Mother's Day: `'second sunday of may'`

#### Relative expression chains

Fourth Advent Sunday: `'11/26, next Sunday, +3 weeks'`<br>
The expressions are executed one after the other, seperated by komma.<br>
The starting date is 01.01. Of the requested year.


### Date anchor

As shown above, the Easter Sunday is represented in the config files by an `E` as a reserved anchor, for relatively-based days (eg` E-2` for Good Friday, etc.).

If necessary, you can also create your own anchors. Valid anchor characters are single uppercase letters `A` to` Z`, except `E` (RegEx: `^[A-DF-Z]$`). `E` is reserved for the Easter Sunday and can therefore not be overwritten.

After the class has been instantiated, a complete, fixed date incl. year for the anchor must be defined. This can be specified as a String or DateTime[Immutable] object.

**Example:** Assuming we want to use the anchor `R` for the Russian Easter festival in the config in order to be able to depict relative dates based on it, we could define it as follows:

``` php
$holiday = new HolidayCalendar($configFile);

$year = 2016;
// Calculation of the Russian Easter date for 2016

// ...

// Let's assume that for the year 2016 it's the 15.05
$russianEasterDate = '2016-05-15';  // as string or DateTime[Immutable] object
$holiday->setDateAnchor('R', $russianEasterDate);

$aHolidayList = $holiday->createHolidaysList($year);
```

Now you can use `R` (like the `E`) in the config files (`R`,` R+50`, etc.).

### Config name

To identify from which of the config files the entry originates, this is output under the key `config`. This is by default the config filename and can be overwritten if desired. In all output data (which does not return a *bool*), the name is always included - in the following example, see `[config] => de.php`.

Config file `de.php`

``` php
/*
 * To identify the config-entries, from which config-file they come.
 * Overwrite this value, if you need.
*/
basename(__FILE__),
```


``` php
Array
(
    [0] => Array
        (
            [date] => 2016-03-25
            [name] => Karfreitag
            [config] => de.php
            [filter] => Array
                (
                )
        )
)
```
