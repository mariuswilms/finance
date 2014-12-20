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
		return $this->_sum()->getAmount();
	}

	// @return Currency|array
	public function getCurrency($split = false) {
		if ($split) {
			return $this->_sum('currency');
		}
		return $this->_sum()->getCurrency();
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
		return $this->_sum()->greaterThan($value);
	}

	public function lessThan(MoneyInterface $value) {
		return $this->_sum()->lessThan($value);
	}

	public function equals(MoneyInterface $value) {
		return $this->_sum()->equals($value);
	}

	public function isZero() {
		return $this->_sum()->isZero();
	}

	/* Helpers */

	// @return Money|array
	protected function _sum($by = null) {
		if ($by) {
			$byMethod = 'get' . ucfirst($by);
			$results = [];

			foreach ($this->_calculations as $calculation) {
				$method = key($calculation);
				$value  = current($calulation);

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
		$result = new NullPrice();

		foreach ($this->_calculations as $calculation) {
			$method = key($calculation);
			$value  = current($calulation);

			$result = $result->{$method}($value);
		}
		return $result;
	}
}

?>