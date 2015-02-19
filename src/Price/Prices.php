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

namespace AD\Finance\Price;

use AD\Finance\Money;
use AD\Finance\Money\NullMoney;
use AD\Finance\Price;
use AD\Finance\Price\PriceInterface;
use AD\Finance\Price\NullPrice;

// Allows mixing prices with different currencies and rates.
class Prices {

	protected $_data = [];

	public function __construct(array $data = []) {
		$this->_data = $data;
	}

	/* Access */

	public function sum() {
		return $this->_data;
	}

	/* Calculation */

	// @return Prices
	public function add(PriceInterface $value) {
		if ($value->isZero()) {
			return clone $this;
		}
		$data = $this->_data;

		$currency = (string) $value->getCurrency();
		$rate = $value->getRate();

		if (!isset($data[$rate][$currency])) {
			$data[$rate][$currency] = new NullPrice();
		}
		$data[$rate][$currency] = $data[$rate][$currency]->add($value);

		return new Prices($data);
	}

	// @return Prices
	public function subtract(PriceInterface $value) {
		if ($value->isZero()) {
			return clone $this;
		}
		$data = $this->_data;

		$currency = (string) $value->getCurrency();
		$rate = $value->getRate();

		if (!isset($data[$rate][$currency])) {
			$data[$rate][$currency] = new NullPrice();
		}
		$data[$rate][$currency] = $data[$rate][$currency]->subtract($value);

		return new Prices($data);
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