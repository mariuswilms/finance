<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace Finance;

use Finance\Money;

/**
 * This test is inherited from Sebastian Bergmann's "Money" project and is:
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * The test has been modified to use our money object instead of the original
 * project one. This allows us to verify that our object behaves the same
 * as the one from Sebastian.
 *
 * Commented tests indicate that this feature has not been implemented and
 * that the behavior of our class differs from the original.
 *
 * @link https://github.com/sebastianbergmann/money
 */
class MoneyCompatBergmannTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @covers			  \Finance\Money::__construct
	 * @uses			  \Finance\Currency
	 * @expectedException \InvalidArgumentException
	 */
	public function testExceptionIsRaisedForInvalidConstructorArguments()
	{
		new Money(null, new Currency('EUR'));
	}

	/**
	 * @covers			  \Finance\Money::__construct
	 * @covers			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Currency
	 * @expectedException \InvalidArgumentException
	 */
	public function testExceptionIsRaisedForInvalidConstructorArguments2()
	{
		new Money(0, null);
	}

	/**
	 * @covers			  \Finance\Money::fromString
	 * @uses			  \Finance\Currency
	 * @expectedException \InvalidArgumentException
	 */
	// public function testExceptionIsRaisedForInvalidConstructorArguments3()
	// {
	// 	Money::fromString(1234, new Currency('EUR'));
	// }

	/**
	 * @covers \Finance\Money::__construct
	 * @covers \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Currency
	 */
	public function testObjectCanBeConstructedForValidConstructorArguments()
	{
		$m = new Money(0, new Currency('EUR'));

		$this->assertInstanceOf('Finance\\Money', $m);

		return $m;
	}

	/**
	 * @covers \Finance\Money::__construct
	 * @covers \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Currency
	 */
	public function testObjectCanBeConstructedForValidConstructorArguments2()
	{
		$m = new Money(0, 'EUR');

		$this->assertInstanceOf('Finance\\Money', $m);

		return $m;
	}

	/**
	 * @covers \Finance\Money::fromString
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Currency
	 */
	// public function testObjectCanBeConstructedFromStringValue()
	// {
	// 	$this->assertEquals(
	// 		new Money(1234, new Currency('EUR')),
	// 		Money::fromString('12.34', new Currency('EUR'))
	// 	);
	// }

	/**
	 * @covers \Finance\Money::fromString
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Currency
	 */
	// public function testObjectCanBeConstructedFromStringValue2()
	// {
	// 	$this->assertEquals(
	// 		new Money(1234, new Currency('EUR')),
	// 		Money::fromString('12.34', 'EUR')
	// 	);
	// }

	/**
	 * @covers	\Finance\Money::getAmount
	 * @depends testObjectCanBeConstructedForValidConstructorArguments
	 */
	public function testAmountCanBeRetrieved(Money $m)
	{
		$this->assertEquals(0, $m->getAmount());
	}

	/**
	 * @covers	\Finance\Money::getCurrency
	 * @uses	\Finance\Currency
	 * @depends testObjectCanBeConstructedForValidConstructorArguments
	 */
	public function testCurrencyCanBeRetrieved(Money $m)
	{
		$this->assertEquals(new Currency('EUR'), $m->getCurrency());
	}

	/**
	 * @covers \Finance\Money::add
	 * @covers \Finance\Money::newMoney
	 * @covers \Finance\Money::assertSameCurrency
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Money::getCurrency
	 * @uses   \Finance\Money::assertIsInteger
	 * @uses   \Finance\Currency
	 */
	public function testAnotherMoneyObjectWithSameCurrencyCanBeAdded()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(2, new Currency('EUR'));
		$c = $a->add($b);

		$this->assertEquals(1, $a->getAmount());
		$this->assertEquals(2, $b->getAmount());
		$this->assertEquals(3, $c->getAmount());
	}

	/**
	 * @covers			  \Finance\Money::add
	 * @covers			  \Finance\Money::newMoney
	 * @covers			  \Finance\Money::assertSameCurrency
	 * @covers			  \Finance\Money::assertIsInteger
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Money::getAmount
	 * @uses			  \Finance\Money::getCurrency
	 * @uses			  \Finance\Currency
	 * @expectedException \OverflowException
	 */
	public function testExceptionIsThrownForOverflowingAddition()
	{
		$a = new Money(PHP_INT_MAX, new Currency('EUR'));
		$b = new Money(2, new Currency('EUR'));
		$a->add($b);
	}

	/**
	 * @covers			  \Finance\Money::assertInsideIntegerBounds
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Money::multiply
	 * @uses			  \Finance\Money::castToInt
	 * @uses			  \Finance\Currency
	 * @expectedException \OverflowException
	 */
	public function testExceptionIsRaisedForIntegerOverflow()
	{
		$a = new Money(PHP_INT_MAX, new Currency('EUR'));
		$a->multiply(2);
	}

	/**
	 * @covers			  \Finance\Money::add
	 * @covers			  \Finance\Money::assertSameCurrency
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Money::getAmount
	 * @uses			  \Finance\Money::getCurrency
	 * @uses			  \Finance\Currency
	 * @expectedException \Finance\CurrencyMismatchException
	 */
	public function testExceptionIsRaisedWhenMoneyObjectWithDifferentCurrencyIsAdded()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(2, new Currency('USD'));

		$a->add($b);
	}

	/**
	 * @covers \Finance\Money::subtract
	 * @covers \Finance\Money::newMoney
	 * @covers \Finance\Money::assertSameCurrency
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Money::getCurrency
	 * @uses   \Finance\Money::assertIsInteger
	 * @uses   \Finance\Currency
	 */
	public function testAnotherMoneyObjectWithSameCurrencyCanBeSubtracted()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(2, new Currency('EUR'));
		$c = $b->subtract($a);

		$this->assertEquals(1, $a->getAmount());
		$this->assertEquals(2, $b->getAmount());
		$this->assertEquals(1, $c->getAmount());
	}

	/**
	 * @covers			  \Finance\Money::subtract
	 * @covers			  \Finance\Money::newMoney
	 * @covers			  \Finance\Money::assertSameCurrency
	 * @covers			  \Finance\Money::assertIsInteger
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Money::getAmount
	 * @uses			  \Finance\Money::getCurrency
	 * @uses			  \Finance\Currency
	 * @expectedException \OverflowException
	 */
	public function testExceptionIsThrownForOverflowingSubtraction()
	{
		$a = new Money(-PHP_INT_MAX, new Currency('EUR'));
		$b = new Money(2, new Currency('EUR'));
		$a->subtract($b);
	}

	/**
	 * @covers			  \Finance\Money::subtract
	 * @covers			  \Finance\Money::assertSameCurrency
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Money::getAmount
	 * @uses			  \Finance\Money::getCurrency
	 * @uses			  \Finance\Currency
	 * @expectedException \Finance\CurrencyMismatchException
	 */
	public function testExceptionIsRaisedWhenMoneyObjectWithDifferentCurrencyIsSubtracted()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(2, new Currency('USD'));

		$b->subtract($a);
	}

	/**
	 * @covers \Finance\Money::negate
	 * @covers \Finance\Money::newMoney
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Currency
	 */
	public function testCanBeNegated()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = $a->negate();

		$this->assertEquals(1, $a->getAmount());
		$this->assertEquals(-1, $b->getAmount());
	}

	/**
	 * @covers \Finance\Money::multiply
	 * @covers \Finance\Money::newMoney
	 * @covers \Finance\Money::castToInt
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Money::assertInsideIntegerBounds
	 * @uses   \Finance\Currency
	 */
	public function testCanBeMultipliedByAFactor()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = $a->multiply(2);

		$this->assertEquals(1, $a->getAmount());
		$this->assertEquals(2, $b->getAmount());
	}

	/**
	 * @covers			  \Finance\Money::multiply
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Currency
	 * @expectedException \InvalidArgumentException
	 */
	public function testExceptionIsRaisedWhenMultipliedUsingInvalidRoundingMode()
	{
		$a = new Money(1, new Currency('EUR'));
		$a->multiply(2, null);
	}

	/**
	 * @covers \Finance\Money::allocateToTargets
	 * @covers \Finance\Money::newMoney
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Currency
	 */
	// public function testCanBeAllocatedToNumberOfTargets()
	// {
	// 	$a = new Money(99, new Currency('EUR'));
	// 	$r = $a->allocateToTargets(10);

	// 	$this->assertEquals(
	// 		array(
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(10, new Currency('EUR')),
	// 			new Money(9, new Currency('EUR'))
	// 		),
	// 		$r
	// 	);
	// }

	/**
	 * @covers \Finance\Money::extractPercentage
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Money::getCurrency
	 * @uses   \Finance\Money::subtract
	 * @uses   \Finance\Money::assertSameCurrency
	 * @uses   \Finance\Money::assertIsInteger
	 * @uses   \Finance\Money::assertInsideIntegerBounds
	 * @uses   \Finance\Money::castToInt
	 * @uses   \Finance\Money::newMoney
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Currency
	 */
	// public function testPercentageCanBeExtracted()
	// {
	// 	$original = new Money(10000, new Currency('EUR'));
	// 	$extract  = $original->extractPercentage(21);

	// 	$this->assertEquals(new Money(8264, new Currency('EUR')), $extract['subtotal']);
	// 	$this->assertEquals(new Money(1736, new Currency('EUR')), $extract['percentage']);
	// }

	/**
	 * @covers			  \Finance\Money::allocateToTargets
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Currency
	 * @expectedException \InvalidArgumentException
	 */
	// public function testExceptionIsRaisedWhenTryingToAllocateToInvalidNumberOfTargets()
	// {
	// 	$a = new Money(0, new Currency('EUR'));
	// 	$a->allocateToTargets(null);
	// }

	/**
	 * @covers \Finance\Money::allocateByRatios
	 * @covers \Finance\Money::newMoney
	 * @covers \Finance\Money::castToInt
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Money::assertInsideIntegerBounds
	 * @uses   \Finance\Currency
	 */
	// public function testCanBeAllocatedByRatios()
	// {
	// 	$a = new Money(5, new Currency('EUR'));
	// 	$r = $a->allocateByRatios(array(3, 7));

	// 	$this->assertEquals(
	// 		array(
	// 			new Money(2, new Currency('EUR')),
	// 			new Money(3, new Currency('EUR'))
	// 		),
	// 		$r
	// 	);
	// }

	/**
	 * @covers \Finance\Money::compareTo
	 * @covers \Finance\Money::assertSameCurrency
	 * @uses   \Finance\Money::__construct
	 * @uses   \Finance\Money::handleCurrencyArgument
	 * @uses   \Finance\Money::getAmount
	 * @uses   \Finance\Money::getCurrency
	 * @uses   \Finance\Currency
	 */
	// public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency()
	// {
	// 	$a = new Money(1, new Currency('EUR'));
	// 	$b = new Money(2, new Currency('EUR'));

	// 	$this->assertEquals(-1, $a->compareTo($b));
	// 	$this->assertEquals(1, $b->compareTo($a));
	// 	$this->assertEquals(0, $a->compareTo($a));
	// }

	/**
	 * @covers	\Finance\Money::greaterThan
	 * @covers	\Finance\Money::assertSameCurrency
	 * @uses	\Finance\Money::__construct
	 * @uses	\Finance\Money::handleCurrencyArgument
	 * @uses	\Finance\Money::compareTo
	 * @uses	\Finance\Money::getAmount
	 * @uses	\Finance\Money::getCurrency
	 * @uses	\Finance\Currency
	 * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
	 */
	public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency2()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(2, new Currency('EUR'));

		$this->assertFalse($a->greaterThan($b));
		$this->assertTrue($b->greaterThan($a));
	}

	/**
	 * @covers	\Finance\Money::lessThan
	 * @covers	\Finance\Money::assertSameCurrency
	 * @uses	\Finance\Money::__construct
	 * @uses	\Finance\Money::handleCurrencyArgument
	 * @uses	\Finance\Money::compareTo
	 * @uses	\Finance\Money::getAmount
	 * @uses	\Finance\Money::getCurrency
	 * @uses	\Finance\Currency
	 * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
	 */
	public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency3()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(2, new Currency('EUR'));

		$this->assertFalse($b->lessThan($a));
		$this->assertTrue($a->lessThan($b));
	}

	/**
	 * @covers	\Finance\Money::equals
	 * @covers	\Finance\Money::assertSameCurrency
	 * @uses	\Finance\Money::__construct
	 * @uses	\Finance\Money::handleCurrencyArgument
	 * @uses	\Finance\Money::compareTo
	 * @uses	\Finance\Money::getAmount
	 * @uses	\Finance\Money::getCurrency
	 * @uses	\Finance\Currency
	 * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
	 */
	public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency4()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(1, new Currency('EUR'));

		$this->assertEquals(0, $a->compareTo($b));
		$this->assertEquals(0, $b->compareTo($a));
		$this->assertTrue($a->equals($b));
		$this->assertTrue($b->equals($a));
	}

	/**
	 * @covers	\Finance\Money::greaterThanOrEqual
	 * @covers	\Finance\Money::assertSameCurrency
	 * @uses	\Finance\Money::__construct
	 * @uses	\Finance\Money::handleCurrencyArgument
	 * @uses	\Finance\Money::greaterThan
	 * @uses	\Finance\Money::equals
	 * @uses	\Finance\Money::compareTo
	 * @uses	\Finance\Money::getAmount
	 * @uses	\Finance\Money::getCurrency
	 * @uses	\Finance\Currency
	 * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
	 */
	public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency5()
	{
		$a = new Money(2, new Currency('EUR'));
		$b = new Money(2, new Currency('EUR'));
		$c = new Money(1, new Currency('EUR'));

		$this->assertTrue($a->greaterThanOrEqual($a));
		$this->assertTrue($a->greaterThanOrEqual($b));
		$this->assertTrue($a->greaterThanOrEqual($c));
		$this->assertFalse($c->greaterThanOrEqual($a));
	}

	/**
	 * @covers	\Finance\Money::lessThanOrEqual
	 * @covers	\Finance\Money::assertSameCurrency
	 * @uses	\Finance\Money::__construct
	 * @uses	\Finance\Money::handleCurrencyArgument
	 * @uses	\Finance\Money::lessThan
	 * @uses	\Finance\Money::equals
	 * @uses	\Finance\Money::compareTo
	 * @uses	\Finance\Money::getAmount
	 * @uses	\Finance\Money::getCurrency
	 * @uses	\Finance\Currency
	 * @depends testCanBeComparedToAnotherMoneyObjectWithSameCurrency
	 */
	public function testCanBeComparedToAnotherMoneyObjectWithSameCurrency6()
	{
		$a = new Money(1, new Currency('EUR'));
		$b = new Money(1, new Currency('EUR'));
		$c = new Money(2, new Currency('EUR'));

		$this->assertTrue($a->lessThanOrEqual($a));
		$this->assertTrue($a->lessThanOrEqual($b));
		$this->assertTrue($a->lessThanOrEqual($c));
		$this->assertFalse($c->lessThanOrEqual($a));
	}

	/**
	 * @covers			  \Finance\Money::compareTo
	 * @covers			  \Finance\Money::assertSameCurrency
	 * @uses			  \Finance\Money::__construct
	 * @uses			  \Finance\Money::handleCurrencyArgument
	 * @uses			  \Finance\Money::getCurrency
	 * @uses			  \Finance\Currency
	 * @expectedException \Finance\CurrencyMismatchException
	 */
	// public function testExceptionIsRaisedWhenComparedToMoneyObjectWithDifferentCurrency()
	// {
	// 	$a = new Money(1, new Currency('EUR'));
	// 	$b = new Money(2, new Currency('USD'));

	// 	$a->compareTo($b);
	// }

	/**
	 * @covers	 \Finance\Money::jsonSerialize
	 * @uses	 \Finance\Money::__construct
	 * @uses	 \Finance\Currency
	 * @uses	 \Finance\Money::handleCurrencyArgument
	 * @requires PHP 5.4.0
	 */
	// public function testCanBeSerializedToJson()
	// {
	// 	$this->assertEquals(
	// 		'{"amount":1,"currency":"EUR"}',
	// 		json_encode(new EUR(1))
	// 	);
	// }
}

?>
