<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__).'\BNPReaderFaker.php');

/**
 * @covers Byteworks\BankCsvReader\BNPReader
 */
final class BNPReaderTest extends TestCase
{
	/**
	 * @return array
	 */
	public function providerCounterpartBic(): array
	{
		return [
			['0000  BIC KREDBEBB REFERTE OPDRACHTGEVER', 'KREDBEBB'],
      ['0000  BIC KREDBEBB    VIA WEB BANKING', 'KREDBEBB'],
      ['0000  BIC KREDBEBB MEDEDELING', 'KREDBEBB'],
		];
	}

  /**
   * Test if an Bic is recognized
   *
	 * @dataProvider providerCounterpartBic
	 */
  public function testParseCounterpartBic($input, $expected): void
  {
    $faker = new BNPReaderFaker();
    $this->assertSame($expected, $faker->parseCounterpartBic($input));
  }
  
	/**
	 * @return array
	 */
	public function providerMessage(): array
	{
		return [
			['XXX MEDEDELING : TEST MEDEDELING UITGEVOERD OP', 'TEST MEDEDELING'],
      ['XXX MEDEDELING : TEST MEDEDELING BANKREFERENTIE', 'TEST MEDEDELING'],
      ['XXX MEDEDELING : /123 A/-/C BANKREFERENTIE', '/123 A/-/C'],
      ['XXX MEDEDELING : TEST MEDEDELING', 'TEST MEDEDELING'],
		];
	}

  /**
   * Test if an Bic is recognized
   *
	 * @dataProvider providerMessage
	 */
  public function testParseMessage($input, $expected): void
  {
    $faker = new BNPReaderFaker();
    $this->assertSame($expected, $faker->parseMessage($input));
  }
  

	/**
	 * @return array
	 */
	public function providerCounterpartName(): array
	{
		return [
			['BNPREADER TEST BE06 7983 9921 3122  BIC KREDBEBB MEDEDELING : ZONDAG PER MAAND VALUTADATUM', 'BE06 7983 9921 3122', 'BNPREADER TEST'],
      ['BNPREADER TEST BE06798399213122 BIC KREDBEBB    VIA WEB BANKING ZONDER MEDEDELING VALUTADATUM', 'BE06 7983 9921 3122', 'BNPREADER TEST'],
			['VAN REKENING NR BE06 7983 9921 3122 VAN BNPREADER TEST VALUTADATUM', 'BE06 7983 9921 3122', 'BNPREADER TEST'],
      ['MET KAART 1234 56XX XXXX X789 0 BNPREADER FIRM     STAD 01/01/2019 VALUTADATUM', null, 'BNPREADER FIRM STAD'],
      ['EEN BERICHT ZONDER BEGUNSTIGDE UITGEVOERD OP 01-01 VALUTADATUM', null, null],
		];
	}

  /**
   * Test if an Bic is recognized
   *
	 * @dataProvider providerCounterpartName
	 */
  public function testParseCounterpart($input, $iban, $expected): void
  {
    $faker = new BNPReaderFaker();
    $this->assertSame($expected, $faker->parseCounterpartName($input, $iban));
  }
}