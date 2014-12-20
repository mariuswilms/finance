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

	public function testAddingWithMixedTaxRates() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2000, 'EUR', 'net', 7)); // 2140

		$expected = 4000;
		$this->assertEquals($expected, $subject->getNet()->getAmount());

		$expected = 2380 + 2140;
		$this->assertEquals($expected, $subject->getGross()->getAmount());

		$expected = 380 + 140;
		$this->assertEquals($expected, $subject->getTax());
	}

	public function testAddingWithMixedTypes() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2300, 'EUR', 'gross', 19)); // net 2000

		$expected = 4000;
		$this->assertEquals($expected, $subject->getNet()->getAmount());

		$expected = 2380 + 2380;
		$this->assertEquals($expected, $subject->getGross()->getAmount());

		$expected = 380 + 380;
		$this->assertEquals($expected, $subject->getTax());
	}

	public function testAddingWithMixedTypesAndRates() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2140, 'EUR', 'gross', 7)); // net 2000

		$expected = 4000;
		$this->assertEquals($expected, $subject->getNet()->getAmount());

		$expected = 2380 + 2140;
		$this->assertEquals($expected, $subject->getGross()->getAmount());

		$expected = 380 + 140;
		$this->assertEquals($expected, $subject->getTax());
	}
}

?>