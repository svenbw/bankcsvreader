# bankcsvreader
Reads CSV files with bank statements from Belgian banks.
Supports (BNP Paribas / KBC / Rabobank)

## Installation

For now this is only a github repository.
One should import the repository code using composer.

```terminal
composer install
```

## Usage

Use Composer's autoloader in the application to automatically load the dependencies.
To use the package in the application one should use the following code:

```php
require 'vendor/autoload.php';
use Byteworks\BankCsvReader\Reader;
use Byteworks\BankCsvReader\BankCodes;
```

To import the bankstatements:

```php
// Using the autodetect feature
$reader = Reader::createFromFile(__DIR__ . '/bank-statements-as-csv.csv');
$transactions = $reader->getTransactions();

// Now, one can loop trough the individual transactions:
foreach ($transactions as $transaction)
{
  echo "Counterpart: ".$transaction->counterpart_account_iban." ".$transaction->counterpart_name."\n";
  echo "Date . . . : ".$transaction->date." Valuta date:".$transaction->valuta_date."\n";
  echo "Amount . . : ".$transaction->amount."\n";
  echo "Message. . : ".$transaction->message." / ".$transaction->message_ogm."\n";
};
```

### Additional Info

BNP Paribas Fortis puts all data in one field in the CSV, the library tries to parse and split the
info in different fields to provide better feedback, filtering.
The tests contain a wrapper class which exposes the protected functions that parse the description field,
so they can be tested.


## Contribute

Feel free to contribute in any way. As an example you may: 
* Trying out the code
* Create a PR
* Create issues if you find problems
* Reply to other people's issues
* Fix issues

### Running the test code

To test the code phpunit is used.

```terminal
composer test
```

or:

```terminal
./vendor/bin/phpunit
```
