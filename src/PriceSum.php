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
use Exception;
use InvalidArgumentException;

class PriceSum {

	protected $_add = [];

	protected $_subtract = [];

	public function getNet() {
		$result = new NullPrice();

		foreach ($this->_add as $item) {
			$result = $result->add($item->getNet()->removeTaxRate());
		}
		return $result->getNet();
	}

	public function getGross() {
		$result = new NullPrice();

		foreach ($this->_add as $item) {
			$result = $result->add($item->getGross()->removeTaxRate());
		}
		return $result->getGross();
	}

	public function getTax() {
		if (!$gross = $this->getGross()) {
			return new NullPrice();
		}
		if (!$net = $this->getNet()) {
			return new NullPrice();
		}
		return $gross->subtract($net);
	}

	// Assumes all prices in sum have the same currency -
	// as enforced by price classes.
	public function getCurrency() {
		if (!$this->_add && !$this->_subtract) {
			throw new Exception("Cannot get currency from empty sum.");
		}
		$price = reset($this->_add ?: $this->_subtract);
		return $price->getCurrency();
	}

	public function add(Price $value) {
		$this->_add[] = $value;

		return clone $this;
	}

	public function subtract(Price $value) {
		$this->_subtract[] = $value;

		return clone $this;
	}

	public function greaterThan(Price $value) {
		$result = new NullPrice();

		foreach ($this->_add as $item) {
			$result = $result->add($item->getNet()->removeTaxRate());
		}
		foreach ($this->_subtract as $item) {
			$result = $result->subtract($item->getNet()->removeTaxRate());
		}
		return $result->greaterThan($value);
	}

	public function isZero() {
		foreach ($this->_add as $item) {
			if (!$item->isZero()) {
				return false;
			}
		}
		foreach ($this->_subtract as $item) {
			if (!$item->isZero()) {
				return false;
			}
		}
		return true;
	}
}

?>