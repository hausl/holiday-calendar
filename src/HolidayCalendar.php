<?php

/*
 * HolidayCalendar
 *
 * Version 2017-04-27 alpha
 *
 * Github: https://github.com/hausl/holiday-calendar
 *
 * License: CC BY 3.0 https://creativecommons.org/licenses/by/3.0/
 *
 */

class HolidayCalendar
{

    const
        DATE_FORMAT_ISO   = 'Y-m-d',
        DATE_FORMAT_YEAR  = 'Y',
        EASTERDATE_ANCHOR = 'E';

    private
        $filePath,
        $lang,
        $oDateTimeZone,
        $fallbackLang = 'de',
        $aHolidayData = [],
        $aFilter = [],
        $aDateAnchors = [],
        $ignoreEmptyFilterConfigItems = false;


    public function __construct($mConfig, $filePath = null)
    {
        if ($filePath !== null) {
            $this->filePath = $filePath;
        } else {
            $this->filePath = getcwd();
        }

        $this->setDateTimezone( new DateTimeZone(date_default_timezone_get()) );

        $this->setLang($this->fallbackLang);

        // load config file(s)
        if ( !is_array($mConfig) ) {
            $this->loadConfigFile($mConfig);
        } else {
            array_walk($mConfig, [$this, 'loadConfigFile']);
        }
    }


    public function setDateTimezone(DateTimeZone $dtZone)
    {
        $this->oDateTimeZone = $dtZone;
        return $this;
    }


    public function setLang($l)
    {
        $this->lang = $l;
        return $this;
    }


    public function setFallbackLang($l)
    {
        $this->fallbackLang = $l;
        return $this;
    }


    public function setFilter(array $aFilter)
    {
        $this->aFilter = $aFilter;
        return $this;
    }


    public function setIgnoreEmptyFilterConfigItems($bool)
    {
        $this->ignoreEmptyFilterConfigItems = $bool;
        return $this;
    }


    public function setDateAnchor($anchor, $date)
    {
        $pattern = '/^[A-DF-Z]$/';
        if ( !preg_match($pattern, $anchor, $match) ) {
            throw new Exception("DateAnchor '" . $anchor . "' does not match the expected pattern " . $pattern);
        }

        $dt = $this->toDateTimeImmutable($date);
        $this->aDateAnchors[$anchor] = $dt;
        return $this;
    }


    public function getLang() {
        return $this->lang;
    }


    public function getFallbackLang() {
        return $this->fallbackLang;
    }


    public function createHolidaysList($year, $returnSortedASC = true)
    {
        $aOutput = [];

        foreach ($this->aHolidayData as $aHolidayData) {

            // skip config-entries with empty filter
            if ( empty($aHolidayData['filter']) && $this->ignoreEmptyFilterConfigItems ) {
                continue;
            }

            // skip entries with no matching filter (but entries with empty filter are allways showed)
            if (
                !empty($this->aFilter)  // filter is set
                && count( array_intersect($this->aFilter, $aHolidayData['filter']) ) === 0  // no match
                && !empty($aHolidayData['filter'])  // filter config-file not empty
            ) {
                continue;
            }

            $dt = $this->dateExpressionToDateTime($aHolidayData['date'], $year, $aHolidayData['config']);

            // skip if entry-year doesn't match the input-parameter-year
            if ($dt->format(self::DATE_FORMAT_YEAR) != $year)  {
                continue;
            }

            // get holiday name in the requested language
            if ( isset($aHolidayData['names'][$this->lang]) ) {
                $holidayName = $aHolidayData['names'][$this->lang];
            } else {
                // use name in default language
                $holidayName = $aHolidayData['names'][$this->fallbackLang];
            }

            $aOutput[] = $this->createDefaultSubArray(
                $dt->format(self::DATE_FORMAT_ISO),
                $holidayName,
                $aHolidayData['config'],
                $aHolidayData['filter']
            );
        }

        if ($returnSortedASC) {
            array_multisort($aOutput, SORT_ASC);
        }
        return $aOutput;
    }


    public function findHolidayByName($toFind, $year)
    {
        $aHolidays = $this->createHolidaysList($year);
        $aOutput = [];

        foreach ($aHolidays as $aHolidayData) {
            if ( mb_stripos($aHolidayData['name'], $toFind) !== false ) {
                $aOutput[] = $this->createDefaultSubArray(
                    $aHolidayData['date'],
                    $aHolidayData['name'],
                    $aHolidayData['config'],
                    $aHolidayData['filter']
                );
            }
        }
        return $aOutput;
    }


    public function findHolidayByDate($date = '')
    {
        $dt = $this->toDateTimeImmutable($date);
        $year = $dt->format(self::DATE_FORMAT_YEAR);
        $isoDate = $dt->format(self::DATE_FORMAT_ISO);

        $aHolidays = $this->createHolidaysList($year);
        $aOutput = [];

        foreach ($aHolidays as $aHolidayData) {
            if ($aHolidayData['date'] == $isoDate) {
                $aOutput[] = $this->createDefaultSubArray(
                    $aHolidayData['date'],
                    $aHolidayData['name'],
                    $aHolidayData['config'],
                    $aHolidayData['filter']
                );
            }
        }
        return $aOutput;
    }


    public function isHoliday($date = '')
    {
        return !empty($this->findHolidayByDate($date));
    }


    private function loadConfigFile($configFile)
    {
        $filePathComplete = $this->filePath . '/' . $configFile;

        if (!is_readable($filePathComplete)) {
            throw new Exception(
                sprintf("Could not open config-file '%s%s'", $filePathComplete, $configFile)
            );
        }

        // load config from file
        list($configIdent, $aContent) = require $filePathComplete;

        // add 'config' value to each entry
        array_walk(
            $aContent,
            function (&$aEntry) use ($configIdent) {
                $aEntry['config'] = $configIdent;
            }
        );
        // add data to current config
        $this->aHolidayData = array_merge($this->aHolidayData, $aContent);
    }


    private function dateExpressionToDateTime($expr, $year, $configFile)
    {
        // set easter-date
        $this->aDateAnchors[self::EASTERDATE_ANCHOR] = $this->createEasterDateTime($year);

        //  an anchor or starts with an anchor, e.g. 'E' or 'E+1'
        if ( preg_match('/^([A-Z])[+-]?/', $expr, $match) ) {
            #var_dump($match);
            if ( !isset($this->aDateAnchors[$match[1]]) ) {
                throw new Exception(
                    sprintf("DateAnchor '%s' (in config '%s', expression '%s') is undefined.", $match[1], $configFile, $expr)
                );
            }
            return $this->aDateAnchors[$match[1]]->modify($expr . ' days');
        }

        // chain e.g. '11/26, next Sunday, +3 weeks'
        if ( strpos($expr, ',') !== false ) {
            $dt = $this->toDateTimeImmutable($year . '-01-01');
            foreach ( explode(',', $expr) as $e ) {
                $dt = $dt->modify( trim($e) );
            }
            return $dt;
        }

        // a date like 5-5 or 12-25 (m-d)
        if ( preg_match('/^[0-9]{1,2}-[0-9]{1,2}$/', $expr) ) {
            return $this->toDateTimeImmutable($year . '-' . $expr);
        }

        // anything else, try to parse by DateTime
        return $this->toDateTimeImmutable($expr);
    }


    private function createEasterDateTime($year)
    {
        /*
          Why i used this instead of easter_date(), see:
          http://php.net/manual/en/function.easter-date.php#refsect1-function.easter-date-notes
        */
        $base = $this->toDateTimeImmutable($year . '-03-21');
        $interval = sprintf('P%sD', easter_days($year));
        return $base->add(new DateInterval($interval));
    }


    private function toDateTimeImmutable($in)
    {
        if ($in instanceof DateTime) {
            // already a DateTime object
            return DateTimeImmutable::createFromMutable($in);
        }

        if ($in instanceof DateTimeImmutable) {
            // already a DateTimeImmutable object
            return $in;
        }

        // others
        return new DateTimeImmutable($in, $this->oDateTimeZone);
    }


    private function createDefaultSubArray($date, $name, $config, $filter)
    {
        return [
            'date'   => $date,
            'name'   => $name,
            'config' => $config,
            'filter' => $filter
        ];
    }

}
