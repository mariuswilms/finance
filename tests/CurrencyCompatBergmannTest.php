<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace AD\Finance;

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
	 * @covers			  \AD\Finance\Currency::__construct
	 * @expectedException \InvalidArgumentException
	 */
	public function testExceptionIsRaisedForInvalidConstructorArgument() {
		new Currency(null);
	}

	/**
	 * @covers \AD\Finance\Currency::__construct
	 */
	public function testCanBeConstructedFromUppercaseString() {
		$c = new Currency('EUR');

		$this->assertInstanceOf('AD\\Finance\\Currency', $c);

		return $c;
	}

	/**
	 * @covers \AD\Finance\Currency::__construct
	 */
	public function testCanBeConstructedFromLowercaseString() {
		$c = new Currency('eur');

		$this->assertInstanceOf('AD\\Finance\\Currency', $c);
	}

	/**
	 * @backupStaticAttributes enabled
	 * @covers \AD\Finance\Currency::addCurrency
	 * @uses   \AD\Finance\Currency::__construct
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
			'AD\Finance\Currency',
			new Currency('BTC')
		);
	}

	/**
	 * @covers	\AD\Finance\Currency::__toString
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testCanBeCastToString(Currency $c) {
		$this->assertEquals('EUR', (string)$c);
	}

	/**
	 * @covers	\AD\Finance\Currency::getCurrencyCode
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testCurrencyCodeCanBeRetrieved(Currency $c) {
		$this->assertEquals('EUR', $c->getCurrencyCode());
	}

	/**
	 * @covers	\AD\Finance\Currency::getDefaultFractionDigits
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testDefaultFractionDigitsCanBeRetrieved(Currency $c) {
		$this->assertEquals(2, $c->getDefaultFractionDigits());
	}

	/**
	 * @covers	\AD\Finance\Currency::getDisplayName
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testDisplayNameCanBeRetrieved(Currency $c) {
		$this->assertEquals('Euro', $c->getDisplayName());
	}

	/**
	 * @covers	\AD\Finance\Currency::getNumericCode
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testNumericCodeCanBeRetrieved(Currency $c) {
		$this->assertEquals(978, $c->getNumericCode());
	}

	/**
	 * @covers	\AD\Finance\Currency::getSubUnit
	 * @depends testCanBeConstructedFromUppercaseString
	 */
	public function testSubUnitCanBeRetrieved(Currency $c) {
		$this->assertEquals(100, $c->getSubUnit());
	}
}
