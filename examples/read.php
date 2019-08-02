<?php

require __DIR__ . '/../vendor/autoload.php';

use Byteworks\BankCsvReader\Reader;
use Byteworks\BankCsvReader\BankCodes;

$reader = Reader::createFromFile(__DIR__ . '/../tests/csv/test-bnp.csv'); //, BankCodes::KBC);
$transactions = $reader->getTransactions();

foreach ($transactions as $transaction)
{
  var_dump($transaction);
}
