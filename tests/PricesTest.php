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

	public function testAddingWithMixedNetRatesFailsOverGetNet() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2000, 'EUR', 'net', 7)); // 2140

		$this->setExpectedException('Exception');
		$subject->getNet();
	}

	public function testAddingWithMixedNetRatesFailsOverGetGross() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2000, 'EUR', 'net', 7)); // 2140

		$this->setExpectedException('Exception');
		$subject->getGross();
	}

	public function testAddingWithMixedTypes() {
		$subject = new Prices();

		$subject = $subject->add($a = new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add($b = new Price(2380, 'EUR', 'gross', 19)); // net 2000

		$expected = 2000 + 2000;
		$this->assertEquals($expected, $subject->getNet()->getAmount());

		$expected = 2380 + 2380;
		$this->assertEquals($expected, $subject->getGross()->getAmount());

		$expected = 380 + 380;
		$this->assertEquals($expected, $subject->getTax()->getAmount());
	}
}

?>