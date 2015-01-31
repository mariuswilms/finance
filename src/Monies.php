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

use Finance\Money;
use Finance\MoneyInterface;
use Finance\NullMoney;

class Monies implements MoneyInterface {

	protected $_calculations = [];

	/* Access */

	// @return integer
	public function getAmount() {
		return $this->_singleSum()->getAmount();
	}

	// @return Currency|array
	public function getCurrency($split = false) {
		if ($split) {
			return $this->_splitSum('currency');
		}
		return $this->_singleSum()->getCurrency();
	}

	/* Calculation (lazy) */

	// @return Monies
	public function add(MoneyInterface $value) {
		$this->_calculations[] = [__FUNCTION__ => $value];
		return clone $this;
	}

	// @return Monies
	public function subtract(MoneyInterface $value) {
		$this->_calculations[] = [__FUNCTION__ => $value];
		return clone $this;
	}

	/* Comparison */

	public function greaterThan(MoneyInterface $value) {
		return $this->_singleSum()->greaterThan($value);
	}

	public function lessThan(MoneyInterface $value) {
		return $this->_singleSum()->lessThan($value);
	}

	public function equals(MoneyInterface $value) {
		return $this->_singleSum()->equals($value);
	}

	public function isZero() {
		return $this->_singleSum()->isZero();
	}

	/* Helpers */

	// When reducing to a single sum we return and calc with Money.
	// @return Money
	protected function _singleSum() {
		$result = new NullMoney();

		foreach ($this->_calculations as $calculation) {
			$method = key($calculation);
			$value  = current($calculation);

			$result = $result->{$method}($value);
		}
		return $result;
	}

	// @return array An array of Money objects.
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
				$results[$key] = new NullMoney();
			}
			$results[$key] = $results[$key]->{$method}($value);
		}
		return $results;
	}
}

?>