<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

class KBCReader extends BaseReader
{
  private const HEADER_COUNT = 18;
  protected const BANK_CODE = BankCodes::KBC;

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

  public function createTransaction(array $record)
  {
    $transaction = new Transaction();
    
    $transaction->account = FormatHelper::parseIban($record[0]);

    $transaction->counterpart_account_iban = FormatHelper::parseIban($record[12]);
    $transaction->counterpart_account_bic = $record[13];
    $transaction->counterpart_name = $record[14];
    $transaction->counterpart_address = $record[15];

    $transaction->message_ogm = FormatHelper::parseOGM($record[16]);
    if ($transaction->message_ogm === null)
    {
      $transaction->message = $record[17];
    }

    $transaction->date = FormatHelper::parseEUDate($record[5]);
    $transaction->valuta_date = FormatHelper::parseEUDate($record[7]);
    $transaction->amount = FormatHelper::parseNumber($record[8]);

    $transaction->description = $record[6];
    $transaction->reference = $record[4]; // !!! Not unique !!!

    return $transaction;
  }
}