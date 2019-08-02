<?php
declare(strict_types=1);

namespace Byteworks\BankCsvReader;

class FormatHelper
{
  public static function isOGM($value): bool
  {
    if (preg_match('/^(?:[\+|\*]{3})?(\d{3})(?:\/|\-)?(\d{4})(?:\/|\-)?(\d{3})(\d{2})(?:[\+|\*]{3})?$/', $value, $matches) !== 1)
      return false;
    
    $number = (int)($matches[1].$matches[2].$matches[3]);
    $rest = $number % 97;
    if ($rest === 0)
    {
      $rest = 97;
    }
    
    return $rest === (int)$matches[4];
  }
  
  public static function parseOGM($value): ?string
  {
    if (!self::isOGM($value))
      return null;
    
    preg_match('/^(?:[\+|\*]{3})?(\d{3})(?:\/|\-)?(\d{4})(?:\/|\-)?(\d{5})(?:[\+|\*]{3})?$/', $value, $matches);
    return sprintf(
      '+++%s/%s/%s+++',
      $matches[1],
      $matches[2],
      $matches[3]
    );
  }

  public static function isNumber($value): bool
  {
    return (preg_match('/^([\+\-]?\d+)([\.\,]\d{2})$/', $value) === 1);
  }

  public static function parseNumber($value): ?float
  {
    if (preg_match('/^([\+\-]?\d+)[\.\,](\d{2})$/', $value, $matches) === 1)
    {
      return (float) sprintf('%s.%s', $matches[1], $matches[2]);
    }

    return null;
  }

  public static function isEUDate($value): bool
  {
    return (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $value) === 1);
  }

  public static function parseEUDate($value): ?string
  {
    if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/', $value, $matches) === 1)
    {
      return join(array_reverse(array_slice($matches, 1)), '-');
    }
    
    return null;
  }
  
  public static function isDate($value): bool
  {
    return (preg_match('/^([0-9]{4})\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value) === 1);
  }

  public static function parseDate($value): ?string
  {
    if (preg_match('/^([0-9]{4})\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value, $matches) === 1)
    {
      return join(array_slice($matches, 1), '-');
    }
    
    return null;
  }

  public static function isIban($value): bool
  {
    $value = str_replace(' ', '', $value);
    return (preg_match('/^([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}(?:[a-zA-Z0-9]?){0,16})$/', $value) === 1);
  }

  public static function parseIban($value): ?string
  {
    $value = str_replace(' ', '', $value);
    if (preg_match('/^([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}(?:[a-zA-Z0-9]?){0,16})$/', $value, $matches) === 1)
    {
      return wordwrap($matches[1], 4, ' ', true);
    }
    
    return null;
  }
  
}