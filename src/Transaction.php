<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;


class Transaction
{
  public $account;

  public $counterpart_account_iban;
  public $counterpart_account_bic;
  public $counterpart_name;
  public $counterpart_address;

  public $date;
  public $valuta_date;
  public $amount;

  public $message;
  public $message_ogm;
  
  public $reference;
  public $description;
}