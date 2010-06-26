<?php

class aDate
{
  // All date formatters here accept both Unix timestamps and MySQL date or datetime values.
  // These methods output only the date, not the time (except for aDate::time()). Use these methods
  // for consistency within and across our applications.
  
  // By default we now use the I18N format_date helper. But we can override this when we
  // want our favorite English date treatment for an English-only site
  
  // Most compact: Sep 3 (2-digit year follows but only if not current year)
  
  static public function pretty($date)
  {
    $date = self::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Short date format is the closest I18N format to our proprietary version
      return format_date($date, 'd');
    }
    $month = date('F', $date);
    $day = date('j', $date);
    $month = substr($month, 0, 3);
    $year = date('Y', $date);
    $yearNow = date('Y');
    $result = "$month $day";
    if ($year != $yearNow)
    {
      // Switch to 2 digit year for compactness. TBB
      $result .= " '" . substr($year, 2);
    }
    return $result;
  }
  
  // Saturday, 14 January 2009
  
  static public function long($date)
  {
    $date = self::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Long date format is the closest I18N format to our proprietary version
      return format_date($date, 'D');
    }
    return date('l, j F Y', $date);
  }

  // Sat, 14 Jan 2009

  static public function medium($date)
  {
    $date = self::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Long date format is the closest I18N format to our proprietary version
      return format_date($date, 'p');
    }
    return date('D, j M Y', $date);
  }

  // 9/4/09 4PM

  static public function short($date)
  {
    $date = self::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Short date format is the closest I18N format to our proprietary version
      return format_date($date, 'd');
    }
    return date('n/j/y', $date);
  }
  
  static public function date($date, $format)
  {
    if (!in_array($format, array('pretty', 'short', 'medium', 'long')))
    {
      throw new Exception("Unknown or missing date format: $format\n");
    }
    return self::$format($date);
  }
  
  // IN: date as timestamp OR the following formats:
  // YYYY-MM-DD 
  // YYYY-MM-DD hh:mm:ss
  // hh:mm:ss
  // hh:mm:ss by itself is interpreted relative to the current day.
  //
  // OUT: timestamp
  static public function normalize($date)
  {  
    if (preg_match("/^(\d\d\d\d)-(\d\d)-(\d\d)( (\d\d):(\d\d):(\d\d))?$/", $date, $matches))
    {
      if (count($matches) == 4)
      {
        list($dummy1, $year, $month, $day) = $matches;
        $hour = 0;
        $min = 0;
        $sec = 0;
      }
      else
      {
        list($dummy1, $year, $month, $day, $dummy2, $hour, $min, $sec) = $matches;
      }
      $date = mktime($hour, $min, $sec, $month, $day, $year);
    }  
    elseif (preg_match("/^(\d\d):(\d\d):(\d\d)?$/", $date, $matches))
    {
      $now = time();
      $year = date('Y', $now);
      $month = date('n', $now);
      $day = date('j', $now);
      list($dummy1, $hour, $min, $sec) = $matches;
      $date = mktime($hour, $min, $sec, $month, $day, $year);
    }
    return $date;
  }
  
  // By default just calls the I18N format_time. The rest of this description
  // applies only if you set app_a_pretty_english_dates:
  
  // The only variation on our time format is turning on the display
  // of :00 when the time is a round hour, such as 8PM. Set compact
  // to false to bring back :00. Otherwise we remove it
  
  static public function time($date, $compact = true)
  {
    $date = self::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Short time format is the closest I18N format to our proprietary version
      return format_date($date, 't');
    }
    
    $hour = date('g', $date);
    $min = date('i', $date);
    $s = $hour;
    if (($min != 0) || (!$compact))
    {
      $s .= ":$min";
    }
    $s .= date('A', $date);
    return $s;
  }


	// 4:15 PM, Thursday

	static public function dayAndTime($date)
	{
		$date = self::normalize($date);
		if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Short date format is the closest I18N format to our proprietary version
      return format_date($date, 'd');
    }
		$time = self::time($date);
		$day = date('l', $date);
		return $time.', '.$day;
	}

  // January 14, 2009
  
  static public function dayMonthYear($date)
  {
    $date = self::normalize($date);
    if (!sfConfig::get('app_a_pretty_english_dates', false))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Date');
      // Long date format is the closest I18N format to our proprietary version
      return format_date($date, 'D');
    }
    return date('M j, Y', $date);
  }
  
  // Subtracts $date2 from $date1 and returns the difference in whole days.
  // For instance, if $date2 is 2009-09-30 and $date1 is 2009-10-01, the
  // result will be 1.
  
  // Arguments should be Unix timestamps or Doctrine YYYY-MM-DD datestamps
  // representing midnight on two days. Valid results are not guaranteed if 
  // the timestamp does not represent midnight at the start of the day
  static public function differenceDays($date1, $date2)
  {
    $date1 = self::normalize($date1);
    $date2 = self::normalize($date2);
    // This rounding logic allows for the difference to be less or more than a full day due to
    // leap seconds and/or daylight savings time
    return floor(($date1 - $date2) / 86400 + 0.5);
  }
}
