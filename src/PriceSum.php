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
		$result = null;

		foreach ($this->_add as $item) {
			if ($result) {
				$result = $result->add($item->getNet()->removeTaxRate());
			} else {
				$result = $item->getNet()->removeTaxRate();
			}
		}
		return $result;
	}

	public function getGross() {
		$result = null;

		foreach ($this->_add as $item) {
			if ($result) {
				$result = $result->add($item->getGross()->removeTaxRate());
			} else {
				$result = $item->getGross()->removeTaxRate();
			}
		}
		return $result;
	}

	public function getTax() {
		if (!$gross = $this->getGross()) {
			return null;
		}
		if (!$net = $this->getNet()) {
			return null;
		}
		return $gross->subtract($net);
	}

	public function add(Price $value) {
		$this->_add[] = $value;

		return $this;
	}

	public function subtract(Price $value) {
		$this->_subtract[] = $value;

		return $this;
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