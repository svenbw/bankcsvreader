<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

class BNPReader extends BaseReader
{
  private const HEADER_COUNT = 8;

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
    

    $transaction->account = FormatHelper::parseIban($record[7]);

    $transaction->counterpart_account_iban = FormatHelper::parseIban($record[5]);
    $transaction->counterpart_account_bic = $this->parseCounterpartBic($record[6]);
    $transaction->counterpart_name = $this->parseCounterpartName($record[6], $transaction->counterpart_account_iban);
    // $transaction->counterpart_address = N.A.;

    $transaction->message_ogm = $this->parseMessageOGM($record[6]);
    if ($transaction->message_ogm === null)
    {
      $transaction->message = $this->parseMessage($record[6]);
    }

    $transaction->date = FormatHelper::parseEUDate($record[1]);
    $transaction->valuta_date = FormatHelper::parseEUDate($record[2]);
    $transaction->amount = FormatHelper::parseNumber($record[3]);

    $transaction->description = $record[6];
    $transaction->reference = $record[0];

    return $transaction;
  }
  
  protected function parseCounterpartBic(string $value): ?string
  {
    if (preg_match('/^.*? BIC ([A-Z]{6}[0-9A-Z]{2}(?:[0-9A-Z]{3})?) /', $value, $matches) === 1)
    {
      return $matches[1];
    }
    return null;
  }
  
  protected function parseCounterpartName(string $value, ?string $iban): ?string
  {
    $result = null;

    if ($iban === null)
    {
      if ((strlen($value) > 32) && (preg_match('/(.*) \d{2}\/\d{2}\/\d{4} /', substr($value, 32), $matches) === 1))
      {
        $result = $matches[1];
      }
    }
    else
    {
      if (preg_match(sprintf('/(.*) %s /', $iban), $value, $matches) === 1)
      {
        $result = $matches[1];
      }
      
      if (preg_match(sprintf('/(.*) %s /', str_replace(' ', '', $iban)), $value, $matches) === 1)
      {
        $result = $matches[1];
      }
      
      if (preg_match(sprintf('/^VAN REKENING (?:.*) VAN (.*)(?: VALUTADATUM)/'), $value, $matches) === 1)
      {
        $result = $matches[1];
      }
    }
    
    if ($result !== null)
    {
      return preg_replace('!\s+!', ' ', $result);
    }
    return null;
  }
    
  protected function parseMessageOGM(string $value): ?string
  {
    if (preg_match('/^(?:.*?BANKREFERENTIE : )([0-9]{16})/', $value, $matches) === 1)
    {
      return FormatHelper::parseOGM($matches[1]);
    }

    return null;
  }

  protected function parseMessage(string $value): ?string
  {
    if (preg_match('/^.*?MEDEDELING : (.*)(?: BANKREFERENTIE| UITGEVOERD OP)/', $value, $matches) === 1)
    {
      return $matches[1];
    }
    if (preg_match('/^.*?MEDEDELING : (.*)$/', $value, $matches) === 1)
    {
      return $matches[1];
    }

    return null;
  }
}