<?php

require __DIR__ . '/../vendor/autoload.php';

use Byteworks\BankCsvReader\Reader;
use Byteworks\BankCsvReader\BankCodes;

$reader = Reader::createFromFile(__DIR__ . '/../tests/csv/test-bnp.csv');
$transactions = $reader->getTransactions();

foreach ($transactions as $transaction)
{
  echo "Counterpart: ".$transaction->counterpart_name." - ".$transaction->counterpart_account_iban."\n";
  echo "Date . . . : ".$transaction->date." Valuta date:".$transaction->valuta_date."\n";
  echo "Amount . . : ".number_format($transaction->amount, 2)."\n";
  echo "Message. . : ".$transaction->message." / ".$transaction->message_ogm."\n";
  echo "\n";
}

echo "Used: ".$reader->getBankCode()."\n";
