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

namespace AD\Finance;

use AD\Finance\Price;

class PriceTest extends \PHPUnit_Framework_TestCase {

	public function testCreateLeavesAmountUntouched() {
		$subject = new Price(1, 'EUR', 'net', 19);
		$expected = 1;
		$result = $subject->getNet()->getAmount();
		$this->assertEquals($expected, $result);
	}

	public function testSimpleNetToGross() {
		$subject = new Price(2004, 'EUR', 'net', 19);
		$expected = 2385;
		$result = $subject->getGross()->getAmount();
		$this->assertEquals($expected, $result);
	}

	public function testSimpleGrossToNet() {
		$subject = new Price(2000, 'EUR', 'gross', 19);
		$expected = 1681;
		$result = $subject->getNet()->getAmount();
		$this->assertEquals($expected, $result);
	}

	public function testSimpleAdd() {
		$subject = new Price(2000, 'EUR', 'net', 19);

		$expected = 4000;
		$result = $subject->add(new Price(2000, 'EUR', 'net', 19));

		$this->assertEquals($expected, $result->getNet()->getAmount());
	}

	public function testSimpleSubtract() {
		$subject = new Price(4000, 'EUR', 'net', 19);

		$expected = 2000;
		$result = $subject->subtract(new Price(2000, 'EUR', 'net', 19));

		$this->assertEquals($expected, $result->getNet()->getAmount());
	}

	public function testAddingNetOnlyNoInternalTypeConversion() {
		$subject = new Price(2000, 'EUR', 'net', 19);

		$expected = 6000;

		$result = $subject->add(new Price(2000, 'EUR', 'net', 19));
		$result = $result->add(new Price(2000, 'EUR', 'net', 19));

		$this->assertEquals($expected, $result->getNet()->getAmount());
	}

	public function testAddingMixedTypeConversion() {
		$subject = new Price(2000, 'EUR', 'gross', 19);
		$expected = 6000;

		$result = $subject->add(new Price(1681, 'EUR', 'net', 19));
		$result = $result->add(new Price(2000, 'EUR', 'gross', 19));

		$this->assertEquals($expected, $result->getGross()->getAmount());
	}

	public function testTax() {
		$subject = new Price(2004, 'EUR', 'gross', 19);
		$expected = 320;
		$this->assertEquals($expected, $subject->getTax()->getAmount());

		$subject = new Price(2004, 'EUR', 'net', 19);
		$expected = 381;
		$this->assertEquals($expected, $subject->getTax()->getAmount());
	}

	public function testTaxEqualsNetGrossDifference() {
		$subject = new Price(2000, 'EUR', 'gross', 19);

		$net = $subject->getNet()->getAmount();
		$gross = $subject->getGross()->getAmount();

		$tax = $subject->getTax()->getAmount();

		$expected = 319;
		$result = $gross - $net;
		$this->assertEquals($expected, $result);

		$subject = new Price(1680, 'EUR', 'net', 19);

		$net = $subject->getNet()->getAmount();
		$gross = $subject->getGross()->getAmount();

		$tax = $subject->getTax()->getAmount();

		$expected = 319;
		$result = $gross - $net;
		$this->assertEquals($expected, $result);
	}
}

?>