<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

abstract class BaseReader
{
  /**
   * the csv file reader
   *
   * @var handle
   */
  protected $transaction_reader;



  /**
   * set to true if the csv has a header
   *
   * @var bool
   */
  protected $has_header = true;

  /**
   * the field escape character (one character only).
   *
   * @var string
   */
  protected $escape = '\\';

  public function __construct($csv_file)
  {
    if ( ! ini_get('auto_detect_line_endings'))
    {
      ini_set('auto_detect_line_endings', '1');
    }
    
    $this->transaction_reader = new TransactionReader($csv_file, $this->has_header, [$this, 'createTransaction']);

    $this->initializeReader();
    $this->validateData();
  }
  
  /**
   * {@inheritdoc}
   */
  public function __destruct()
  {
    $this->handle = null;
  }

  /**
   * {@inheritdoc}
   */
  public function __clone()
  {
    throw new Exception(sprintf('An object of class %s cannot be cloned', static::class));
  }

  public function getTransactions()
  {
    return $this->transaction_reader;
  }

  abstract protected function initializeReader();
  abstract protected function validateData();
  abstract public function createTransaction(array $record);
  abstract public static function isValidData(array $header, array $row=null): bool;
}