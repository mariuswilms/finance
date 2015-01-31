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

use Finance\MoneyInterface;
use Finance\NullMoney;

// Allows mixing Money objects with differnt currencies.
class Monies {

	protected $_data = [];

	/* Access */

	// @return array An array with Currency as key and Money as value.
	public function sum() {
		return $this->_data;
	}

	/* Calculation */

	// @return Monies
	public function add(MoneyInterface $value) {
		$currency = (string) $value->getCurrency();

		if (!isset($this->_data[$currency])) {
			$this->_data[$currency] = new NullMoney();
		}
		$this->_data[$currency] = $this->_data[$currency]->add($value);
		return clone $this;
	}

	// @return Monies
	public function subtract(MoneyInterface $value) {
		$currency = (string) $value->getCurrency();

		if (!isset($this->_data[$currency])) {
			$this->_data[$currency] = new NullMoney();
		}
		$this->_data[$currency] = $this->_data[$currency]->subtract($value);
		return clone $this;
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