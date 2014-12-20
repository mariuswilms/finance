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

use Finance\PriceInterface;
use Finance\NullMoney;
use Finance\Currency;

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