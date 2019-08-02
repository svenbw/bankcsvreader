<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

use Iterator;
use Countable;
use SplFileObject;
use RuntimeException;
use LogicException;

class TransactionReader implements Iterator, Countable
{
  protected $file_handle;

  /**
   * the field delimiter (one character only).
   *
   * @var string
   */
  protected $delimiter = ',';

  /**
   * the field enclosure character (one character only).
   *
   * @var string
   */
  protected $enclosure = '"';

  /**
   * the field escape character (one character only).
   *
   * @var string
   */
  protected $escape = '\\';

  protected $column_count = null;
  protected $row_count = null;

  protected $callback = null;

  protected $has_header = false;

  public function __construct(string $csv_file, bool $has_header, ?callable $callback)
  {
    try
    {
      $this->file_handle = new SplFileObject($csv_file, 'r');
    }
    catch (RuntimeException $ex)
    {
      throw new Exception\InvalidCSVException($ex->getMessage());
    }
    catch (LogicException $ex)
    {
      throw new Exception\InvalidCSVException($ex->getMessage());
    }
    
    $this->file_handle->setFlags(SplFileObject::DROP_NEW_LINE);
    $this->has_header = $has_header;
    $this->callback = $callback;
  }

  public function setDelimiter(string $delimiter)
  {
    $this->delimiter = $delimiter;
    
    return $this;
  }

  public function setEnclosure(string $delimiter)
  {
    $this->enclosure = $enclosure;
    
    return $this;
  }

  public function setEscape(string $escape)
  {
    $this->escape = $escape;
    
    return $this;
  }

  public function setColumnCount(int $column_count)
  {
    $this->column_count = $column_count;
    
    return $this;
  }

  public function raw_current()
  {
    $current = $this->file_handle->current();
    if (!is_string($current))
      return [];

    return array_slice(str_getcsv($current, $this->delimiter, $this->enclosure, $this->escape), 0, $this->column_count);
  }

  public function current()
  {
    $current = $this->raw_current();
    if ($this->column_count === null)
    {
      return $current;
    }
    
    return call_user_func($this->callback, $current);
  }

  public function key()
  {
    return $this->file_handle->key();
  }

  public function next()
  {
    $this->file_handle->next();
  }
  
  public function rewind()
  {
    $this->file_handle->rewind();
    
    if ($this->has_header)
    {
      $this->file_handle->seek(1);
    }
  }

  public function valid()
  {
    if (!$this->file_handle->valid())
      return false;

    if ($this->column_count === null)
      return true;

    $current = $this->raw_current();
    return count($current) === $this->column_count;
  }
  
  public function count()
  {
    if ($this->row_count === null)
    {
      $current_line = $this->file_handle->key();
      $this->file_handle->seek($this->file_handle->getSize());
      $this->row_count = $this->file_handle->key();
      $this->file_handle->seek($current_line);
    }

    return $this->row_count;
  }
}