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

use SebastianBergmann\Money\Money;
use Exception;
use InvalidArgumentException;

class MoneySum {

	protected $_add = [];

	protected $_subtract = [];

	public function getMoney() {
		if ($result = $this->_sum()) {
			return $result;
		}
	}

	public function getAmount() {
		if ($result = $this->_sum()) {
			return $result->getAmount();
		}
	}

	protected function _sum() {
		$result = null;

		foreach ($this->_add as $item) {
			if ($result) {
				$result = $result->add($item);
			} else {
				$result = clone $item;
			}
		}
		foreach ($this->_subtract as $item) {
			if ($result) {
				$result = $result->subtract($item);
			} else {
				$result = $item->negate();
			}
		}
		return $result;
	}

	// Assumes all money values in sum have the same currency -
	// as enforced by money classes.
	public function getCurrency() {
		if (!$this->_add && !$this->_subtract) {
			return null;
		}

		$reference = $this->_add ?: $this->_subtract;
		$price = reset($reference);
		return $price->getCurrency();
	}

	public function add($value) {
		if ($value->getAmount() === 0) {
			return $this;
		}
		$this->_add[] = $value;

		return clone $this;
	}

	public function subtract($value) {
		if ($value->getAmount() === 0) {
			return $this;
		}
		$this->_subtract[] = $value;

		return clone $this;
	}

	public function greaterThan($value) {
		$result = $this->_sum();

		if (!$result) {
			return $value->getAmount() > 0;
		}
		return $result->greaterThan($value);
	}

	public function isZero() {
		foreach ($this->_add as $item) {
			if ($item->getAmount() !== 0) {
				return false;
			}
		}
		foreach ($this->_subtract as $item) {
			if ($item->getAmount() !== 0) {
				return false;
			}
		}
		return true;
	}
}

?>