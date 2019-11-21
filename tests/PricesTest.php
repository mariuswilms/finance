<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 * Copyright (c) 2017 Atelier Disko - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace AD\Finance;

use AD\Finance\Price\Prices;

class PricesTest extends \PHPUnit_Framework_TestCase {

	public function testAddingWithMixedNetRatesOverGetNet() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2000, 'EUR', 'net', 7)); // 2140

		$sum = $subject->sum();

		$expected = 2000;
		$this->assertEquals($expected, $sum[19]['EUR']->getNet()->getAmount());

		$expected = 2000;
		$this->assertEquals($expected, $sum[7]['EUR']->getNet()->getAmount());
	}

	public function testAddingWithMixedNetRatesOverGetGross() {
		$subject = new Prices();

		$subject = $subject->add(new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add(new Price(2000, 'EUR', 'net', 7)); // 2140

		$sum = $subject->sum();

		$expected = 2380;
		$this->assertEquals($expected, $sum[19]['EUR']->getGross()->getAmount());

		$expected = 2140;
		$this->assertEquals($expected, $sum[7]['EUR']->getGross()->getAmount());
	}

	public function testAddingWithMixedTypes() {
		$subject = new Prices();

		$subject = $subject->add($a = new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add($b = new Price(2380, 'EUR', 'gross', 19)); // net 2000

		$sum = $subject->sum();

		$expected = 2000 + 2000;
		$this->assertEquals($expected, $sum[19]['EUR']->getNet()->getAmount());

		$expected = 2380 + 2380;
		$this->assertEquals($expected, $sum[19]['EUR']->getGross()->getAmount());
	}

	public function testAddingRepeatedTypeConversion() {
		$subject = new Prices();

		$subject = $subject->add($a = new Price(2000, 'EUR', 'net', 19)); // gross 2380
		$subject = $subject->add($b = new Price(2380, 'EUR', 'gross', 19)); // net 2000

		$sum = $subject->sum();

		$expected = 2000 + 2000;
		$this->assertEquals($expected, $sum[19]['EUR']->getNet()->getAmount());

		$expected = 2380 + 2380;
		$this->assertEquals($expected, $sum[19]['EUR']->getGross()->getAmount());
	}

	public function testAddingSummingCorrectRound() {
		$subject = new Prices();
		$subject = $subject->add((new Price(5000, 'EUR', 'net', 19))->multiply(0.25));
		$subject = $subject->add((new Price(5000, 'EUR', 'net', 19))->multiply(0.25));
		$subject = $subject->add((new Price(5000, 'EUR', 'net', 19))->multiply(0.25));
		$subject = $subject->add((new Price(5000, 'EUR', 'net', 19))->multiply(0.25));
		$subject = $subject->add((new Price(5000, 'EUR', 'net', 19))->multiply(0.5));

		$sum = $subject->sum();

		$this->assertEquals(7500, $sum[19]['EUR']->getNet()->getAmount());
		$this->assertEquals(8925, $sum[19]['EUR']->getGross()->getAmount());
	}
}

?>
