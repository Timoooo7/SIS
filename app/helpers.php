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
