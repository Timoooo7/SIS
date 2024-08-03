<?php
if (!function_exists('format_currency')) {
  function format_currency($amount, $currency = 'IDR', $locale = null)
  {
    $locale = $locale ?? config('app.locale');
    $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
    $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
    return $formatter->formatCurrency($amount, $currency);
  }
}

if (!function_exists('format_date')) {
  function format_date($date, $type = 'day')
  {
    switch ($type) {
      case 'day':
        return $date->format('D');
        break;
      case 'date':
        return $date->format('d');
        break;
      case 'month':
        return $date->format('M');
        break;
      case 'year':
        return $date->format('Y');
        break;
      default:
        return $date->format('d M Y');
        break;
    }
  }

  if (!function_exists('format_date_time')) {
    function format_date_time($date_time, $type = 'auto')
    {
      if ($type == 'auto') {
        switch (true) {
          case $date_time->diffInDays(now()) < 7:
            return week_date_time($date_time);
            break;

          case now()->year - $date_time->year < 1:
            return year_date_time($date_time);
            break;

          default:
            return compact_date_time($date_time);
            break;
        }
      } elseif ($type == 'complete') {
        return complete_date_time($date_time);
      }
    }

    function week_date_time($date_time)
    {
      return $date_time->format('D, H:i');
    }

    function year_date_time($date_time)
    {
      return $date_time->format('d M Y H:i');
    }

    function complete_date_time($date_time)
    {
      return $date_time->format('D, d M Y H:i:s a');
    }

    function compact_date_time($date_time)
    {
      return $date_time->format('d/m/y H:i');
    }
  }
}
