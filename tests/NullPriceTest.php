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

use Finance\Price;

class NullPriceTest extends \PHPUnit_Framework_TestCase {

	public function testAmount() {
		$subject = new NullPrice();

		$expected = 0;
		$result = $subject->getAmount();
		$this->assertEquals($expected, $result);
	}

	public function testCurrency() {
		$subject = new NullPrice();

		$expected = null;
		$result = $subject->getCurrency();
		$this->assertEquals($expected, $result);
	}

	public function testType() {
		$subject = new NullPrice();

		$expected = 'net';
		$result = $subject->getType();
		$this->assertEquals($expected, $result);
	}

	public function testNet() {
		$subject = new NullPrice();

		$result = $subject->getNet();
		$this->assertInstanceOf('Finance\NullMoney', $result);
	}

	public function testGross() {
		$subject = new NullPrice();

		$result = $subject->getGross();
		$this->assertInstanceOf('Finance\NullMoney', $result);
	}

	public function testTax() {
		$subject = new NullPrice();

		$result = $subject->getTax();
		$this->assertInstanceOf('Finance\NullMoney', $result);
	}

	public function testRate() {
		$subject = new NullPrice();

		$expected = 0;
		$result = $subject->getRate();
		$this->assertEquals($expected, $result);
	}

	public function testMultiply() {
		$subject = new NullPrice();

		$expected = $subject;
		$result = $subject->multiply(2);
		$this->assertEquals($expected, $result);
	}

	public function testAdd() {
		$subject = new NullPrice();
		$price = new Price(2000, 'EUR', 'net', 19);

		$expected = new Price(2000, 'EUR', 'net', 19);
		$result = $subject->add($price);
		$this->assertEquals($expected, $result);
	}

	public function testSubtract() {
		$subject = new NullPrice();
		$price = new Price(2000, 'EUR', 'net', 19);

		$expected = new Price(-2000, 'EUR', 'net', 19);
		$result = $subject->subtract($price);
		$this->assertEquals($expected, $result);
	}

	public function testNegate() {
		$subject = new NullPrice();

		$expected = $subject;
		$result = $subject->negate();
		$this->assertEquals($expected, $result);
	}

	public function testGreaterThan() {
		$subject = new NullPrice();

		$price = new NullPrice();
		$result = $subject->greaterThan($price);
		$this->assertFalse($result);

		$price = new Price(0, 'EUR', 'net', 19);
		$result = $subject->greaterThan($price);
		$this->assertFalse($result);

		$price = new Price(2000, 'EUR', 'net', 19);
		$result = $subject->greaterThan($price);
		$this->assertFalse($result);

		$price = new Price(-2000, 'EUR', 'net', 19);
		$result = $subject->greaterThan($price);
		$this->assertTrue($result);
	}

	public function testLessThan() {
		$subject = new NullPrice();

		$price = new NullPrice();
		$result = $subject->lessThan($price);
		$this->assertFalse($result);

		$price = new Price(0, 'EUR', 'net', 19);
		$result = $subject->lessThan($price);
		$this->assertFalse($result);

		$price = new Price(2000, 'EUR', 'net', 19);
		$result = $subject->lessThan($price);
		$this->assertTrue($result);

		$price = new Price(-2000, 'EUR', 'net', 19);
		$result = $subject->lessThan($price);
		$this->assertFalse($result);
	}

	public function testEquals() {
		$subject = new NullPrice();

		$price = new NullPrice();
		$result = $subject->equals($price);
		$this->assertTrue($result);

		// TODO Unsure
		// $price = new Price(0, 'EUR', 'net', 19);
		// $result = $subject->equals($price);
		// $this->assertFalse($result);

		$price = new Price(2000, 'EUR', 'net', 19);
		$result = $subject->equals($price);
		$this->assertFalse($result);

		$price = new Price(-2000, 'EUR', 'net', 19);
		$result = $subject->equals($price);
		$this->assertFalse($result);
	}
}

?>