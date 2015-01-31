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

use Finance\Prices;

class PricesTest extends \PHPUnit_Framework_TestCase {

	public function testAddingWithMixedNetRatesOverGetNet() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2000, 'EUR', 'net', 7)); // 2140

		$sum = $subject->sum();

		$expected = 2000;
		$this->assertEquals($expected, $sum['EUR'][19]->getNet()->getAmount());

		$expected = 2000;
		$this->assertEquals($expected, $sum['EUR'][7]->getNet()->getAmount());
	}

	public function testAddingWithMixedNetRatesOverGetGross() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2000, 'EUR', 'net', 7)); // 2140

		$sum = $subject->sum();

		$expected = 2380;
		$this->assertEquals($expected, $sum['EUR'][19]->getGross()->getAmount());

		$expected = 2140;
		$this->assertEquals($expected, $sum['EUR'][7]->getGross()->getAmount());
	}

	public function testAddingWithMixedTypes() {
		$subject = new Prices();

		$subject = $subject->add($a = new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add($b = new Price(2380, 'EUR', 'gross', 19)); // net 2000

		$sum = $subject->sum();

		$expected = 2000 + 2000;
		$this->assertEquals($expected, $sum['EUR'][19]->getNet()->getAmount());

		$expected = 2380 + 2380;
		$this->assertEquals($expected, $sum['EUR'][19]->getGross()->getAmount());
	}

	public function testAddingRepeatedTypeConversion() {
		$subject = new Prices();

		$subject = $subject->add($a = new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add($b = new Price(2380, 'EUR', 'gross', 19)); // net 2000

		$sum = $subject->sum();

		$expected = 2000 + 2000;
		$this->assertEquals($expected, $sum['EUR'][19]->getNet()->getAmount());

		$expected = 2380 + 2380;
		$this->assertEquals($expected, $sum['EUR'][19]->getGross()->getAmount());
	}
}

?>