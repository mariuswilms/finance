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

class NullMoney {

	public function getAmount() {
		return 0;
	}

	public function getCurrency() {
		return null;
	}

	public function multiply($factor) {
		return clone $this;
	}

	public function add(PriceInterface $value) {
		return clone $value;
	}

	public function subtract(PriceInterface $value) {
		return $value->negate();
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