<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace Finance\Money;

use Finance\Currency;
use Finance\Money\MoneyInterface;

class NullMoney implements MoneyInterface {

	/* Access */

	public function getAmount() {
		return 0;
	}

	public function getCurrency() {
		return new Currency('XXX');
	}

	/* Calculation */

	public function multiply($factor) {
		return clone $this;
	}

	public function add(MoneyInterface $value) {
		return clone $value;
	}

	public function subtract(MoneyInterface $value) {
		return $value->negate();
	}

	public function negate() {
		return clone $this;
	}

	/* Comparison */

	public function greaterThan(MoneyInterface $value) {
		return $this->getAmount() > $value->getAmount();
	}

	public function lessThan(MoneyInterface $value) {
		return $this->getAmount() < $value->getAmount();
	}

	public function equals(MoneyInterface $value) {
		return $this->getAmount() === $value->getAmount();
	}

	public function isZero() {
		return true;
	}
}

?>
