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

class Prices {

	protected $_calculations = [];

	/* Access */

	// @return integer
	public function getAmount() {
		return $this->_singleSum('net')->getAmount();
	}

	// @return Money
	public function getNet() {
		return $this->_singleSum('net');
	}

	// @return Money
	public function getGross() {
		return $this->_singleSum('gross');
	}

	// @return Money
	public function getTax() {
		$gross = $this->_singleSum('gross');
		$net = $this->_singleSum('net');

		return new Money(
			$gross->getAmount() - $net->getAmount(),
			$net->getCurrency()
		);
	}

	// @return Currency|array
	public function getCurrency($split = false) {
		if ($split) {
			return $this->_splitSum('currency');
		}
		return $this->_singleSum('net')->getCurrency();
	}

	// @return string|array
	public function getType($split = false) {
		if ($split) {
			return $this->_splitSum('type');
		}
		return 'net';
	}

	/* Calculation (lazy) */

	// @return Prices
	public function add(PriceInterface $value) {
		$this->_calculations[] = [__FUNCTION__ => $value];
		return clone $this;
	}

	// @return Prices
	public function subtract(PriceInterface $value) {
		$this->_calculations[] = [__FUNCTION__ => $value];
		return clone $this;
	}

	/* Comparison */

	public function greaterThan(PriceInterface $value, $by = 'net') {
		$byMethod = 'get' . ucfirst($by);
		return $this->_singleSum($by)->greaterThan($value->{$byMethod}());
	}

	public function lessThan(PriceInterface $value, $by = 'net') {
		$byMethod = 'get' . ucfirst($by);
		return $this->_singleSum($by)->lessThan($value->{$byMethod}());
	}

	public function equals(PriceInterface $value, $by = 'net') {
		$byMethod = 'get' . ucfirst($by);
		return $this->_singleSum($by)->equals($value->{$byMethod}());
	}

	public function isZero() {
		return $this->_sum()->isZero();
	}

	/* Helpers */

	// When reducing to a single sum we return and calc with Money.
	// @return Money
	protected function _singleSum($by) {
		$byMethod = 'get' . ucfirst($by);
		$result = new NullMoney();

		foreach ($this->_calculations as $calculation) {
			$method = key($calculation);
			$value  = current($calculation);

			if (is_object($value)) {
				$value = $value->{$byMethod}();
			}
			$result = $result->{$method}($value);
		}
		return $result;
	}

	// @return array An array of Price(s).
	protected function _splitSum($by) {
		$byMethod = 'get' . ucfirst($by);
		$results = [];

		foreach ($this->_calculations as $calculation) {
			$method = key($calculation);
			$value  = current($calculation);

			if (is_object($key = $value->{$byMethod}())) {
				$key = (string) $key;
			}
			if (!isset($results[$key])) {
				$results[$key] = new NullPrice();
			}
			$results[$key] = $results[$key]->{$method}($value);
		}
		return $results;
	}
}

?>