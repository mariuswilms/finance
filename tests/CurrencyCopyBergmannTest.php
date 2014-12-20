<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Atelier Disko - All rights reserved.
 *
 * This software is proprietary and confidential. Redistribution
 * not permitted. Unless required by applicable law or agreed to
 * in writing, software distributed on an "AS IS" BASIS, WITHOUT-
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 */

namespace Finance;

/**
 * This test is inherited from Sebastian Bergmann's "Money" project and is:
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * The test has been modified to use our currency object instead of the original
 * project one. This allows us to verify that our object behaves the same
 * as the one from Sebastian.
 *
 * @link https://github.com/sebastianbergmann/money
 */
class CurrencyCopyBergmannTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers			  \Finance\Currency::__construct
	 * @expectedException \InvalidArgumentException
	 */
	public function testExceptionIsRaisedForInvalidConstructorArgument() {
		new Currency(null);
	}

	/**
	 * @covers \Finance\Currency::__construct
	 */
	public function testCanBeConstructedFromUppercaseString() {
		$c = new Currency('EUR');

		$this->assertInstanceOf('Finance\\Currency', $c);

		return $c;
	}

	/**
	 * @covers \Finance\Currency::__construct
	 */
	public function testCanBeConstructedFromLowercaseString() {
		$c = new Currency('eur');

		$this->assertInstanceOf('Finance\\Currency', $c);
	}

	/**
	 * @backupStaticAttributes enabled
	 * @covers \Finance\Currency::addCurrency
	 * @uses   \Finance\Currency::__construct
	 */
	public function testCustomCurrencyCanBeRegistered() {
		Currency::addCurrency(
			'BTC',
			'Bitcoin',
			999,
			4,
			1000
		);

		$this->assertInstanceOf(
			'Finance\Currency',
			new Currency('BTC')
		);
	}

	/**
	 * @covers	\Finance\Currency::__toString
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testCanBeCastToString(Currency $c) {
		$this->assertEquals('EUR', (string)$c);
	}

	/**
	 * @covers	\Finance\Currency::getCurrencyCode
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testCurrencyCodeCanBeRetrieved(Currency $c) {
		$this->assertEquals('EUR', $c->getCurrencyCode());
	}

	/**
	 * @covers	\Finance\Currency::getDefaultFractionDigits
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testDefaultFractionDigitsCanBeRetrieved(Currency $c) {
		$this->assertEquals(2, $c->getDefaultFractionDigits());
	}

	/**
	 * @covers	\Finance\Currency::getDisplayName
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testDisplayNameCanBeRetrieved(Currency $c) {
		$this->assertEquals('Euro', $c->getDisplayName());
	}

	/**
	 * @covers	\Finance\Currency::getNumericCode
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testNumericCodeCanBeRetrieved(Currency $c) {
		$this->assertEquals(978, $c->getNumericCode());
	}

	/**
	 * @covers	\Finance\Currency::getSubUnit
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testSubUnitCanBeRetrieved(Currency $c) {
		$this->assertEquals(100, $c->getSubUnit());
	}
}