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

use Finance\Price;
use Finance\PriceInterface;
use Finance\Money;
use Finance\NullMoney;
use Finance\NullPrice;

// Allows mixing prices with different currencies and rates.
class Prices {

	protected $_data = [];

	/* Access */

	public function sum() {
		return $this->_data;
	}

	/* Calculation */

	// @return Prices
	public function add(PriceInterface $value) {
		$currency = (string) $value->getCurrency();
		$rate = $value->getRate();

		if (!isset($this->_data[$currency][$rate])) {
			$this->_data[$currency][$rate] = new NullPrice();
		}
		$this->_data[$currency][$rate] = $this->_data[$currency][$rate]->add($value);
		return clone $this;
	}

	// @return Prices
	public function subtract(PriceInterface $value) {
		$currency = (string) $value->getCurrency();
		$rate = $value->getRate();

		if (!isset($this->_data[$currency][$rate])) {
			$this->_data[$currency][$rate] = new NullPrice();
		}
		$this->_data[$currency][$rate] = $this->_data[$currency][$rate]->subtract($value);
		return clone $this;
	}

	/* Comparison */

	public function isZero() {
		foreach ($this->_data as $currency => $rates) {
			foreach ($rates as $rate => $money) {
				if (!$money->isZero()) {
					return false;
				}
			}
		}
		return true;
	}
}

?>