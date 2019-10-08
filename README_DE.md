# HolidayCalendar (alpha)

Diese Klasse bietet Funktionen, um Feiertage zu berechnen. Ist die Definition eines Feiertages einmal in der Definition (Config Dateien) aufgenommen, kann dieser autom. für die Zukunft oder Vergangenheit berechnet und/oder ausgegeben werden.

Alle Funktionen der Klasse basieren auf diesen Config-Dateien. Da ich aus Österreich bin, sind die Config-Dateien für Österreich und Deutschland mit im Lieferunfang enthalten. Weiters gibt es eine kleine Beispiel-Config mit, nennen wir es - "event days", also keine Feiertage als Beispiel für die Definitionssyntax.

**Wichtig bei Benützung von Composer!**<br>
Die Config-Dateien können benutzt werden, wie sie sind, aber **bitte sicherstellen, dass diese außerhalb des `src` or `vendor` Verzeichnisses gespeichert werden**, da diese sonst von Composer (wenn verwendet) beim Update überschrieben werden.

## Anwendung

### Klasse direkt inkluden

``` php
// je nach verwendetem Pfad, zB
require __DIR__ . '/holiday-calendar/src/HolidayCalendar.php';
```

### Installation via Composer

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/hausl/holiday-calendar"
    }
]
```

### Configs (Feiertagsdefinitionen) laden

``` php
// Config-Datei de.php (Deutschland) laden
$holiday = new HolidayCalendar('de.php', __DIR__.'/myHolidayConfigs');
```

``` php
// mehrere Config-Dateien als Array angeben
$holiday = new HolidayCalendar( ['de.php', 'examples.php'], '/myHolidayConfigs');
```

Wenn der Pfad nicht gesetzt ist, wird das aktuelle Verzeichnis genommen

### Zeitzone (optional)

Zeitzone kann gesetzt werden, sonst wird die aktuelle in PHP gesetzte genutzt.

``` php
$holiday->setDateTimezone( new DateTimeZone('Europe/Berlin') );
```

### Sprache (optional) für die Feiertagsnamen

Standard ist `de`. Wird für die Feiertagsnamen genutzt, für die Ausgabe, als auch die Namenssuche.

``` php
$holiday->setLang('en');
```

Wenn gewünscht, kann die Fallback-Sprache (`de`) auch geändert werden. Diese wird genutzt, wenn die mittels `setLang()` für einen Feiertag in der Definition nicht vorhanden ist. Ist `de` bei default.

``` php
$holiday->setFallbackLang('de');
```

Mittels `getLang()` und `getFallbackLang()` kann der jeweils aktuell gesetzte Wert angezeigt werden.


### Einschränkungen der Daten (optional)

Wenn gewünscht können die Daten für die Ausgabe und/oder Suche beschränkt werden.


#### Einschränken mittels Filter

Die Filter können je nach belieben genutzt werden. In der Sprachdatei für DE habe ich diese für die Bundesländer genutzt.

``` php
// nur Daten mit Filter 'BY' ("Bayern") und alle ohne Filter anzeigen
$holiday->setFilter( ['BY'] ); // Filter immer als Array angeben!
```

Auszug einer Config-Datei, siehe das `filter` Feld ist hier ebenfalls 'BY'.

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


#### Einträge ohne (bzw. leerem) Filter ausschliessen

Wenn dieser Flag auf `true`, gesetzt wird, werden Daten ohne/mit leere Filter ausgeschlossen. Da dies in den beiliegenden Config-Dateien die nationalen Feiertage betrifft, wird man das eher weniger brauchen.

``` php
// alle mit leerer/ohne Filter-Definition ausschliessen
$holiday->setIgnoreEmptyFilterConfigItems(true);
```


### Ausgabe/Suche

**Nicht vergessen! Die Basis für alle Ausgaben und Suchen sind immer die Dateien mit den Datums-Defintionen**

Abhängig welche Methode man nutzt, bekommt man folgendes zurück:

a) Ein *bool* (`true`/`false`), <br>
b) Oder ein Multidimensionales Array mit den Daten. Sind keine Daten vorhanden, wird ein leeres Array zurückgegeben.


#### Pürfen ob ein Datum ein Feiertag (gem. den geladenen Definitionen) ist

``` php
var_dump( $holiday->isHoliday('2015-12-25') );
// true
```

Prüfen ob **heute** ein Feiertag ist - Parameter weglassen.<br>
Alternativ man kann `''`, `'now'` oder `'today'` nutzen.

``` php
var_dump( $holiday->isHoliday() );
// false
```


#### Feiertagsname /-infos per Datum

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

Alternativ, kann auch ein DateTime[Immutable]-Objekt übergeben werden, statt einem String `'2015-12-25'`.

``` php
$dt = new DateTime('2017-10-15');
var_dump( $holiday->findHolidayByDate($dt) );

```

Prüfen ob **heute** ein Feiertag ist - Parameter weglassen.<br>
Alternativ man kann `''`, `'now'` oder `'today'` nutzen.

Beispielsweise wird für den 2017-03-20 ein leeres Array zurückgegeben, da es für diesen Tag keinen Eintrag in den Defintionen gibt.

``` php
print_r( $holiday->findHolidayByDate() );
/*
Array
(
)
*/
```


#### Feiertag nach Namen suchen

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

Es kann auch mehr Ergebnisse bei der Namenssuche geben

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


#### Liste erstellen

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

Ergänzt mit einem Filter 'BW' ("Baden-Württemberg") und einem `foreach()` für 2017

``` php
$holiday = new HolidayCalendar('de.php');  // Deutschland
$holiday->setFilter(['BW']);  // alle mit 'BW' oder leerem/keinen Filter

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

#### Sortierung Ausgabe

Standardmäßig wird das Ausgabe-Array aufsteigend nach Datum und Name sortiert. Ist dies nicht gwünscht, kann `false` als zweiter Parameter in `createHolidaysList()` angegeben werden.

``` php
$aHolidays = $holiday->createHolidaysList(2017, false);
```


#### Method-Chaining

Die Setter-Methoden `setDateTimezone()`, `setFilter()`, and `setIgnoreEmptyFilterConfigItems()`, `setLang()` und `setFallbackLang()` können auch via Method-Chaining verkürzt angegeben werden.

``` php
$aData = $holiday->setFilter(['BY'])->setIgnoreEmptyFilterConfigItems(true)->createHolidaysList(2016);
```


## Config-Dateien

Die Config-Dateien geben ein PHP-Array mit den Defintionen der Feiertage zurück. Es stehen mehre Definitionsmöglichkeiten zur Verfügung.

``` php
[
    'names'  => [                       // Feiertagsname, Sprachabhängig
        'de' => 'Heilige Drei Könige',
        'en' => 'Three holy kings'
    ],
    'date'   => '01-06',                // Datumsausdruck, hier ein fixes Datum eines Jahres: (m-d)
    'filter' => ['BW', 'BY', 'ST']      // Filter, hier "Baden-Württemberg", "Bayern", "Sachsen-Anhalt"
],
```

### Datumsausdrücke

Hier sind die verschiedenen Möglichkeiten für Ausdrücke


#### Eindeutiges, fixes Datum

Ein eindeutiges Datum, nur dieses Jahr wird im Format `Y-m-d` angegeben.

`2016-10-15`

#### Fixes Datum je/pro Jahr

Wiederholt sich jährlich. Wird angegeben mittels `m-d`.

`01-01` Neujahr<br>
`12-25` Erster Weihnachtstag

#### Ostersonntag

Ostersonntag wird mit einem einfachen  `E` angegeben. Dieses Datum ist fix und kann nicht geändert werden. Berechnung erfolgt in der Klasse.

#### Oster-relevante Tage

Pfingstsonntag: `E+50` (Ostersonntag + 50 Tage)<br>
Karfreitag: `E-2` (Ostersonntag - 2 Tage)

#### Relative Datumsausdrücke

Start der Sommerzeit: `'last sunday of march'`<br>
Muttertag: `'second sunday of may'`

#### Relative Ausdrucks-Ketten

Vierter Advent Sonntag: `'11/26, next Sunday, +3 weeks'`<br>
Diese Ausdrücke werden, kommagetrennt, einer nach dem Anderen ausgeführt.<br>
Start ist der 01.01. des angeforderten Jahres.


### Datumsanker

Wie oben gezeigt ist der Ostersonntag als reservierter Anker `E` in den Config-Dateien für Oster-relevante Feiertage angegeben.

Wenn benötigt können auch eigene Anker angelegt werden. Gültige Anker sind `A` bis` Z`, ausgenommen`E` (RegEx: `^[A-DF-Z]$`).
`E` ist für Ostersonntag reserviert und kann nicht überschrieben werden.

Nach Instantiierung muss für den Anker ein vollständiges, fixes Datum definiert werden. Das kann als String oder DateTime[Immutable] angegeben werden.

**Beispiel:** Angenommen wir wollen den Anker `R` für das russische Ostern verwenden, um darauf basierend relative Feiertage darzustellen, kann das wie folgt aussehen:

``` php
$holiday = new HolidayCalendar($configFile);

$year = 2016;
// Berechnung des russ. Osterdatum für 2016

// ...

// Angenommen 2016 ist es der 15.05.
$russianEasterDate = '2016-05-15';  // als String oder DateTime[Immutable] Objekt
$holiday->setDateAnchor('R', $russianEasterDate);

$aHolidayList = $holiday->createHolidaysList($year);
```

Nun kann `R` wie `E` in den Config-Dateien verwendet werden (`R`,` R+50`, etc.).


### Config name

Um zu identifizieren, aus welcher Konfigurationsdatei der Eintrag stammt, wird dieser unter dem Schlüssel `config` ausgegeben. Dies ist standardmäßig der Konfigurationsdateiname und kann bei Bedarf überschrieben werden. In allen Ausgabedaten (die kein *bool* zurückgeben) ist der Name immer enthalten - im folgenden Beispiel siehe `[config] => de.php`.

Config-Datei `de.php`

``` php
/*
 * Identifikation aus welcher Config-Datei der Eintrag kommt
 * Kann überschrieben werden, wenn gewünscht
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
