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
use SebastianBergmann\Money\Currency;
use Exception;
use InvalidArgumentException;

class PriceSum {

	public function add(Price $value) {
		$this->_add[] = $value;

		return $this;
	}

	public function subtract(Price $value) {
		$this->_subtract[] = $value;

		return $this;
	}

	public function getNet() {
		$result = null;

		foreach ($this->_add as $item) {
			if ($result) {
				$result = $result->add($item->getNet());
			} else {
				$result = $item->getNet();
			}
		}
		return $result;
	}

	public function getGross() {
		$result = null;

		foreach ($this->_add as $item) {
			if ($result) {
				$result = $result->add($item->getGross());
			} else {
				$result = $item->getGross();
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
}

?>