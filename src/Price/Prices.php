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

use AD\Finance\Money;
use AD\Finance\Money\NullMoney;
use AD\Finance\Money\Monies;
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

	public function getNet() {
		return $this->_monies(__FUNCTION__);
	}

	public function getGross() {
		return $this->_monies(__FUNCTION__);
	}

	public function getTax() {
		return $this->_monies(__FUNCTION__);
	}

	protected function _monies($method) {
		$result = new Monies();

		foreach ($this->_data as $rate => $currencies) {
			foreach ($currencies as $currency => $price) {
				$result = $result->add($price->$method());
			}
		}
		return $result;
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
