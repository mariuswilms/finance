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

namespace AD\Finance;

use Exception;
use InvalidArgumentException;
use AD\Finance\Currency;
use AD\Finance\Money\MoneyInterface;

class Money implements MoneyInterface {

	protected $_amount;

	protected $_currency;

	public function __construct($amount, $currency) {
		if (!is_integer($amount)) {
			throw new InvalidArgumentException('Money amount must be of type integer.');
		}
		$this->_amount = $amount;

		if (!is_string($currency) && !is_object($currency)) {
			throw new InvalidArgumentException('Money currency must be of type string or object.');
		}
		$this->_currency = is_object($currency) ? $currency : new Currency($currency);
	}

	/* Access */

	public function getAmount() {
		return $this->_amount;
	}

	public function getCurrency() {
		return $this->_currency;
	}

	/* Calculation */

	public function negate() {
		return new Money(
			-1 * $this->_amount,
			$this->_currency
		);
	}

	public function multiply($factor) {
		return new Money(
			$this->_castToInteger($this->_amount * $factor),
			$this->_currency
		);
	}

	public function add(MoneyInterface $value) {
		$us   = $this;
		$them = $value;


		if ($us->isZero()) {
			return clone $them;
		}
		if ($them->isZero()) {
			return clone $us;
		}

		$this->_assertSameCurrency($us, $them);

		return new Money(
			$us->getAmount() + $them->getAmount(),
			$us->getCurrency()
		);
	}

	public function subtract(MoneyInterface $value) {
		$us   = $this;
		$them = $value;


		if ($us->isZero()) {
			return $them->negate();
		}
		if ($them->isZero()) {
			return clone $us;
		}

		$this->_assertSameCurrency($us, $them);

		return new Money(
			$us->getAmount() - $them->getAmount(),
			$us->getCurrency()
		);
	}

	/* Comparison */

	public function isZero() {
		return $this->_amount === 0;
	}

	public function equals(MoneyInterface $value) {
		$this->_assertSameCurrency($this, $value);
		return $this->_amount === $value->getAmount();
	}

	public function lessThan(MoneyInterface $value) {
		$this->_assertSameCurrency($this, $value);
		return $this->_amount < $value->getAmount();
	}

	public function greaterThan(MoneyInterface $value) {
		$this->_assertSameCurrency($this, $value);
		return $this->_amount > $value->getAmount();
	}

	/* Helpers */

	protected function _assertSameCurrency($a, $b) {
		if ($a->getCurrency() != $b->getCurrency()) {
			throw new Exception('Calculation/comparison with differing currencies.');
		}
	}

	protected function _castToInteger($amount) {
		$amount = round($amount, PHP_ROUND_HALF_UP);

		if (abs($amount) > PHP_INT_MAX) {
			throw new OverflowException();
		}
		return intval($amount);
	}
}

?>