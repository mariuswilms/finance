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

use Exception;
use InvalidArgumentException;
use Finance\PriceInterface;

class NullPrice implements PriceInterface {

	public function getMoney() {
		throw new Exception('Not implemented.');
	}

	public function getAmount($type = null) {
		return 0;
	}

	public function getCurrency() {
		return null;
	}

	public function getType() {
		return null;
	}

	public function getTaxRate() {
		return null;
	}

	public function getNet() {
		return clone $this;
	}

	public function getGross() {
		return clone $this;
	}

	public function getTax() {
		return clone $this;
	}

	public function multiply($factor) {
		return clone $this;
	}

	public function add(PriceInterface $value) {
		return clone $value;
	}

	public function subtract(PriceInterface $value) {
		return clone $value;
	}

	public function greaterThan(PriceInterface $value) {
		return false;
	}

	public function isZero() {
		return true;
	}

	public function negate() {
		return clone $this;
	}
}

?>