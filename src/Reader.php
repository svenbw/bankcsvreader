<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

class Reader
{
  public static function createFromFile($csv_file, $bank=null)
  {
    if ($bank === null)
    {
      $bank = FormatDetector::create($csv_file);
    }

    switch ($bank)
    {
      case BankCodes::BNP: 
        return new BNPReader($csv_file);

      case BankCodes::KBC: 
        return new KBCReader($csv_file);

      case BankCodes::RABO: 
        return new RaboReader($csv_file);

      default:
        throw new Exception\UnsupportedTypeException('No readers supporting the given type: ' . $bank);
    }
  }
}