<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * @covers Byteworks\BankCsvReader\FormatDetector
 */
final class FormatDetectorTest extends TestCase
{
	/**
	 * @return array
	 */
	public function providerValidCSV(): array
	{
		return [
			["Volgnummer;Uitvoeringsdatum;Valutadatum;Bedrag;Valuta rekening;TEGENPARTIJ VAN DE VERRICHTING;Details;Rekeningnummer\n", BankCodes::BNP],
			["Rekeningnummer;Rubrieknaam;Naam;Munt;Afschriftnummer;Datum;Omschrijving;Valuta;Bedrag;Saldo;Credit;Debet;Rekening tegenpartij;BIC code tegenpartij;Naam tegenpartij;Adres tegenpartij;gestructureerde mededeling;vrije mededeling\n", BankCodes::KBC],
			["\"Boekingsdatum\";\"Valutadatum\";\"Type verrichting\";\"Bedrag\";\"Valuta\";\"IBAN tegenpartij\";\"Tegenpartij\";\"Mededeling\";\"Referentie\"\n", BankCodes::RABO],
		];
	}

	/**
   * Tests if the format can be detected
   *
	 * @dataProvider providerValidCSV
	 */
  public function testFormatCreatorForValidCSV($csv_data, $expected_bank): void
  {
    $root = vfsStream::setup('root', null, [ 'input.csv' => $csv_data ]);
    
    $this->assertEquals($expected_bank, FormatDetector::create($root->url() . '/input.csv'));
  }
  
	/**
	 * @return array
	 */
	public function providerInvalidCSV(): array
	{
		return [
			["Invalid\n"],
			[""],
			["field1,field2,field3\n"],
		];
	}

	/**
   * Tests if the format detector reports an UnsupportedType exception
   *
	 * @dataProvider providerInvalidCSV
	 */
  public function testFormatCreatorUnsupportedType($csv_data): void
  {
    $this->expectException(Exception\UnsupportedTypeException::class);
    
    $root = vfsStream::setup('root', null, [ 'input.csv' => $csv_data ]);

    FormatDetector::create($root->url() . '/input.csv');
  }

	/**
   * Tests if the format detector reports an InvalidCSV exception
   * if the file does not exist
	 */
  public function testFormatCreatorMissingFile(): void
  {
    $this->expectException(Exception\InvalidCSVException::class);
    
    $root = vfsStream::setup('root', null, [ ]);

    FormatDetector::create($root->url() . '/input.csv');
  }

}
