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
use Finance\NullMoney;
use Finance\NullPrice;

class Prices {

	protected $_calculations = [];

	/* Access */

	// @return integer
	public function getAmount() {
		return $this->_sum()->getAmount();
	}

	// @return Money
	public function getNet($split = false) {
		if ($split) {
			$result = $this->_sum('type');
			return isset($result['net']) ? $result['net'] : new NullMoney();
		}
		return $this->_sum()->getNet();
	}

	// @return Money
	public function getGross($split = false) {
		if ($split) {
			$result = $this->_sum('type');
			return isset($result['gross']) ? $result['gross'] : new NullMoney();
		}
		return $this->_sum()->getGross();
	}

	// @return Money
	public function getTax() {
		return $this->_sum()->getTax();
	}

	// @return Currency|array
	public function getCurrency($split = false) {
		if ($split) {
			return $this->_sum('currency');
		}
		return $this->_sum()->getCurrency();
	}

	// @return string|array
	public function getType($split = false) {
		if ($split) {
			return $this->_sum('type');
		}
		return $this->_sum()->getType();
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

	public function greaterThan(PriceInterface $value) {
		return $this->_sum()->greaterThan($value);
	}

	public function lessThan(PriceInterface $value) {
		return $this->_sum()->lessThan($value);
	}

	public function equals(PriceInterface $value) {
		return $this->_sum()->equals($value);
	}

	public function isZero() {
		return $this->_sum()->isZero();
	}

	/* Helpers */

	// @return Price|array
	protected function _sum($by = null) {
		if ($by) {
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
		$result = new NullPrice();

		foreach ($this->_calculations as $calculation) {
			$method = key($calculation);
			$value  = current($calculation);

			$result = $result->{$method}($value);
		}
		return $result;
	}
}

?>