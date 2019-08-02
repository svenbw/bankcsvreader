<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

class FormatDetector
{
  public static function create($csv_file)
  {
    if ( ! ini_get('auto_detect_line_endings'))
    {
      ini_set('auto_detect_line_endings', '1');
    }
    
    $transaction_reader = new TransactionReader($csv_file, false, null);
    $transaction_reader->setDelimiter(';');

    $header = $transaction_reader->current();
    $transaction_reader->next();
    $row = $transaction_reader->valid() ? $transaction_reader->current() : null;

    $matches = [];
    if (BNPReader::isValidData($header, $row))
    {
      $matches[] = BankCodes::BNP;
    }
    if (KBCReader::isValidData($header, $row))
    {
      $matches[] = BankCodes::KBC;
    }
    if (RaboReader::isValidData($header, $row))
    {
      $matches[] = BankCodes::RABO;
    }

    if (count($matches) !== 1)
    {
      throw new Exception\UnsupportedTypeException();
    }
    
    return $matches[0];
  }
}
