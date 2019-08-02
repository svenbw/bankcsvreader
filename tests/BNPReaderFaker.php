<?php declare(strict_types=1);

/*
 * This class exposes the 'parse' functions as public methods for PHP unit
 *
 * The createTransaction() function transforms an array with one line to the
 * Transaction class.
 * For BNP Paribas Fortis a lot of the information for the Transaction class
 * is combined in one field. The parse* functions pick the information from
 * the passed string (using regex).
 * Exposing these functions for test allows validation of the different parse
 * functions without providing a full record. 
 */
 
namespace Byteworks\BankCsvReader;

class BNPReaderFaker extends BNPReader
{
  public function __construct()
  {
  }

  public function __call(string $name, array $arguments)
  {
    if (strpos($name, 'parse') === 0)
    {
      return call_user_func_array([$this, 'parent::'.$name], $arguments);
    }

  }
}