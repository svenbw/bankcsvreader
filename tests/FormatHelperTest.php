<?php declare(strict_types=1);

namespace Byteworks\BankCsvReader;

use PHPUnit\Framework\TestCase;

/**
 * @covers Byteworks\BankCsvReader\FormatHelper
 */
final class FormatHelperTest extends TestCase
{
	/**
	 * @return array
	 */
	public function providerValidOGM(): array
	{
		return [
			['+++766/1288/54917+++', '+++766/1288/54917+++'],
      ['***280/7619/73130***', '+++280/7619/73130+++'],
			['+++566-7413-83472+++', '+++566/7413/83472+++'],
      ['***095-9858-00029***', '+++095/9858/00029+++'],
      ['655/5653/47947', '+++655/5653/47947+++'],
      ['682-1879-17932', '+++682/1879/17932+++'],
      ['554521745929', '+++554/5217/45929+++'],
		];
	}

	/**
	 * @dataProvider providerValidOGM
	 */
  public function testIsOGM($input): void
  {
    $this->assertTrue(FormatHelper::isOGM($input));
  }
  
	/**
	 * @dataProvider providerValidOGM
	 */
  public function testFormatOGM($input, $expected): void
  {
    $this->assertSame($expected, FormatHelper::parseOgm($input));
  }


  public function testIsInvalidOGM(): void
  {
    $this->assertFalse(FormatHelper::isOGM('+++766/1288/54900+++'));
  }
  
  public function testFormatIsOGMNull(): void
  {
    $this->assertNull(FormatHelper::parseOGM('+++766/1288/54900+++'));
  }

	public function providerValidNumber(): array
	{
		return [
			['1.23', 1.23],
			['4,56', 4.56],
			['-7.89', -7.89],
			['-0,12', -0.12],
			['+3.45', 3.45],
			['+6,78', 6.78],
		];
	}

	/**
	 * @dataProvider providerValidNumber
	 */
  public function testIsNumber($input): void
  {
    $this->assertTrue(FormatHelper::isNumber($input));
  }
  
	/**
	 * @dataProvider providerValidNumber
	 */
  public function testFormatNumber($input, $expected)
  {
    $this->assertEquals($expected, FormatHelper::parseNumber($input));
  }
  
	public function providerInvalidNumber(): array
	{
		return [
			['*1.23'],
      ['4.567'],
		];
	}
  
	/**
	 * @dataProvider providerInvalidNumber
	 */
  public function testIsNumberInvalid($input): void
  {
    $this->assertFalse(FormatHelper::isNumber($input));
  }

	/**
	 * @dataProvider providerInvalidNumber
	 */
  public function testFormatNumberIsNull($input): void
  {
    $this->assertNull(FormatHelper::parseNumber($input));
  }

	/**
	 * @return array
	 */
	public function providerEUDate(): array
	{
		return [
			['31/12/2019', '2019-12-31'],
			//['12/31/2019', '31/12/2019'],
		];
	}

	/**
	 * @dataProvider providerEUDate
	 */
  public function testIsEUDate($input): void
  {
    $this->assertTrue(FormatHelper::isEUDate($input));
  }
  
	/**
	 * @dataProvider providerEUDate
	 */
  public function testFormatEUDate($input, $expected): void
  {
    $this->assertSame($expected, FormatHelper::parseEUDate($input));
  }
  
	/**
	 * @return array
	 */
	public function providerInvalidEUDate(): array
	{
		return [
			['12/31/2019'],
      ['2019/12/31'],
      ['2019-12-31'],
		];
	}

	/**
	 * @dataProvider providerInvalidEUDate
	 */
  public function testIsEUDateInvalid($input): void
  {
    $this->assertFalse(FormatHelper::isEUDate($input));
  }
  
	/**
	 * @dataProvider providerInvalidEUDate
	 */
  public function testFormatEUDateIsNull($input): void
  {
    $this->assertNull(FormatHelper::parseEUDate($input));
  }

	/**
	 * @return array
	 */
	public function providerDate(): array
	{
		return [
			['2019-12-31', '2019-12-31'],
		];
	}

	/**
	 * @dataProvider providerDate
	 */
  public function testIsDate($input): void
  {
    $this->assertTrue(FormatHelper::isDate($input));
  }
  
	/**
	 * @dataProvider providerDate
	 */
  public function testFormatDate($input, $expected): void
  {
    $this->assertSame($expected, FormatHelper::parseDate($input));
  }
  
	/**
	 * @return array
	 */
	public function providerInvalidDate(): array
	{
		return [
			['12/31/2019'],
      ['2019/12/31'],
      ['31-12-2019'],
		];
	}

	/**
	 * @dataProvider providerInvalidDate
	 */
  public function testIsDateInvalid($input): void
  {
    $this->assertFalse(FormatHelper::isDate($input));
  }
  
	/**
	 * @dataProvider providerInvalidDate
	 */
  public function testFormatDateIsNull($input): void
  {
    $this->assertNull(FormatHelper::parseDate($input));
  }
}
