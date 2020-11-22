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

use Finance\Money\MoneyInterface;
use Finance\Money\NullMoney;

// Allows mixing Money objects with differnt currencies.
class Monies {

	protected $_data = [];

	public function __construct(array $data = []) {
		$this->_data = $data;
	}

	/* Access */

	// @return array An array with Currency as key and Money as value.
	public function sum() {
		return $this->_data;
	}

	/* Calculation */

	// @return Monies
	public function add(MoneyInterface $value) {
		if ($value->isZero()) {
			return clone $this;
		}
		$data = $this->_data;
		$currency = (string) $value->getCurrency();

		if (!isset($data[$currency])) {
			$data[$currency] = new NullMoney();
		}
		$data[$currency] = $data[$currency]->add($value);

		return new Monies($data);
	}

	// @return Monies
	public function subtract(MoneyInterface $value) {
		if ($value->isZero()) {
			return clone $this;
		}
		$data = $this->_data;
		$currency = (string) $value->getCurrency();

		if (!isset($data[$currency])) {
			$data[$currency] = new NullMoney();
		}
		$data[$currency] = $data[$currency]->subtract($value);

		return new Monies($data);
	}

	/* Comparison */

	public function isZero() {
		foreach ($this->_data as $currency => $money) {
			if (!$money->isZero()) {
				return false;
			}
		}
		return true;
	}
}

?>
