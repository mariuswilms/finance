<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace AD\Finance\Price;

use AD\Finance\Currency;
use AD\Finance\Money\NullMoney;
use AD\Finance\Price\PriceInterface;

class NullPrice implements PriceInterface {

	public function getAmount() {
		return 0;
	}

	public function getCurrency() {
		return new Currency('XXX');
	}

	public function getType() {
		return 'net';
	}

	public function getRate() {
		return 0;
	}

	public function getNet() {
		return new NullMoney();
	}

	public function getGross() {
		return new NullMoney();
	}

	public function getTax() {
		return new NullMoney();
	}

	public function multiply($factor) {
		return clone $this;
	}

	public function add(PriceInterface $value) {
		return clone $value;
	}

	public function subtract(PriceInterface $value) {
		return $value->negate();
	}

	public function negate() {
		return clone $this;
	}

	public function greaterThan(PriceInterface $value) {
		return $this->getAmount() > $value->getAmount();
	}

	public function lessThan(PriceInterface $value) {
		return $this->getAmount() < $value->getAmount();
	}

	public function equals(PriceInterface $value) {
		return $this->getAmount() === $value->getAmount();
	}

	public function isZero() {
		return true;
	}
}

?>
