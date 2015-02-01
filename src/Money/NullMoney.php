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

namespace AD\Finance\Money;

use AD\Finance\Currency;
use AD\Finance\Money\MoneyInterface;

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