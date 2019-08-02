<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

class RaboReader extends BaseReader
{
  private const HEADER_COUNT = 9;

  public static function isValidData(array $header, array $row=null): bool
  {
    if (count($header) !== self::HEADER_COUNT)
      return false;
    
    return true;
  }

  protected function initializeReader()
  {
    $this->transaction_reader->setDelimiter(';');
  }
  
  protected function validateData()
  {
    $header = $this->transaction_reader->current();

    if (!self::isValidData($header))
    {
      throw new Exception\InvalidCSVFormatException();
    }
    
    $this->transaction_reader->setColumnCount(self::HEADER_COUNT);
  }

  public function createTransaction($record)
  {
    $transaction = new Transaction();
    
    $transaction->account = FormatHelper::parseIban($record[0]);

    $transaction->counterpart_account_iban = FormatHelper::parseIban($record[5]);
    $transaction->counterpart_name = trim($record[6]);
    // $transaction->counterpart_account_bic = N.A.;
    // $transaction->counterpart_address = N.A.;

    $transaction->message_ogm = FormatHelper::parseOGM($record[7]);
    if ($transaction->message_ogm === null)
    {
      $transaction->message = trim($record[7]);
    }

    $transaction->date = FormatHelper::parseDate($record[0]);
    $transaction->valuta_date = FormatHelper::parseDate($record[1]);
    $transaction->amount = FormatHelper::parseNumber($record[3]);

    $transaction->description = $record[2];
    $transaction->reference = $record[8];

    return $transaction;
  }
}