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

class PriceTest extends \PHPUnit_Framework_TestCase {

	public function testCreateLeavesAmountUntouched() {
		$subject = new Price(1, 'EUR', 'net');
		$expected = 1;
		$result = $subject->getNet()->getAmount();
		$this->assertEquals($expected, $result);

		$subject = new Price(1.2, 'EUR', 'net');
		$expected = 1.2;
		$result = $subject->getNet()->getAmount();
		$this->assertEquals($expected, $result);
	}

	public function testSimpleNetToGross() {
		$subject = new Price(20, 'EUR', 'net', 19);
		$expected = 23.8000;
		$result = $subject->getGross()->getAmount();
		$this->assertEquals($expected, $result, '', 0.0001);
	}

	public function testSimpleGrossToNet() {
		$subject = new Price(20, 'EUR', 'gross', 19);
		$expected = 16.8067;
		$result = $subject->getNet()->getAmount();
		$this->assertEquals($expected, $result, '', 0.0001);
	}

	public function testLimitedNetGrossConversionPrecisionLost() {
		$subject = new Price(20, 'EUR', 'net', 19);

		$expected = 20;

		$result = $subject->getGross();
		$result = $result->getNet();
		$result = $result->getGross();
		$result = $result->getNet()->getAmount();

		$this->assertEquals($expected, $result, '', 0.0001);
	}

	public function testSimpleAdd() {
		$subject = new Price(20, 'EUR', 'net', 19);

		$expected = 40;
		$result = $subject->add(new Price(20, 'EUR', 'net', 19));

		$this->assertEquals($expected, $result->getNet()->getAmount(), '', 0.0001);
	}

	public function testSimpleSubtract() {
		$subject = new Price(40, 'EUR', 'net', 19);

		$expected = 20;
		$result = $subject->subtract(new Price(20, 'EUR', 'net', 19));

		$this->assertEquals($expected, $result->getNet()->getAmount(), '', 0.0001);
	}

	public function testAddingNetOnlyNoInternalTypeConversion() {
		$subject = new Price(20, 'EUR', 'net', 19);

		$expected = 60;

		$result = $subject->add(new Price(20, 'EUR', 'net', 19));
		$result = $result->add(new Price(20, 'EUR', 'net', 19));

		$this->assertEquals($expected, $result->getNet()->getAmount(), '', 0.0001);
	}

	public function testAddingMixedTypeConversion() {
		$subject = new Price(20, 'EUR', 'gross', 19);

		$expected = 60;

		$result = $subject->add(new Price(16.8067, 'EUR', 'net', 19));
		$result = $result->add(new Price(20, 'EUR', 'gross', 19));

		$this->assertEquals($expected, $result->getGross()->getAmount(), '', 0.0001);
	}

	public function testTax() {
		$subject = new Price(20, 'EUR', 'net', 19);

		$net = $subject->getNet()->getAmount();
		$gross = $subject->getGross()->getAmount();

		$tax = $subject->getTax()->getAmount();

		$expected = 3;
		$this->assertEquals($expected, $tax, '', 0.0001);

		$result = $gross - $net;
		$expected = 3.800;
		$this->assertEquals($expected, $result, '', 0.0001);
	}
}

?>