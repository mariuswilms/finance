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

use AD\Finance\Money\MoneyInterface;
use AD\Finance\Money\NullMoney;

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