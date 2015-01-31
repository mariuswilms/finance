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
use OverflowException;
use Finance\Money;
use Finance\Currency;
use Finance\PriceInterface;

/**
 * A class which is loosely based upon the Money class. In contrast to money
 * Price can handle net/gross conversions and internally works with floating
 * point numbers in order to minimize error when adding/subtracting prices.
 *
 * Price can optionally work without a given tax rate but will throw an exception
 * if a conversion from the internal type is attempted.
 */
class Price implements PriceInterface {

	protected $_amount;

	protected $_currency;

	protected $_type;

	protected $_rate;

	public function __construct($amount, $currency, $type, $rate) {
		if (!is_integer($amount)) {
			throw new InvalidArgumentException('Price amount must be of type integer.');
		}
		$this->_amount = $amount;

		if (!is_string($currency) && !is_object($currency)) {
			throw new InvalidArgumentException('Price currency must be of type string or object.');
		}
		$this->_currency = is_object($currency) ? $currency : new Currency($currency);

		if ($type !== 'net' && $type !== 'gross') {
			throw new InvalidArgumentException('Price type must be either `net` or `gross`.');
		}
		$this->_type = $type;

		if (!is_integer($rate)) {
			throw new InvalidArgumentException('Price tax rate must be of type integer.');
		}
		$this->_rate = $rate;
	}

	/* Access */

	public function getAmount() {
		return $this->_amount;
	}

	public function getCurrency() {
		return $this->_currency;
	}

	public function getType() {
		return $this->_type;
	}

	// @return integer
	public function getRate() {
		return $this->_rate;
	}

	// @return Money
	public function getNet() {
		if ($this->_type === 'net') {
			return new Money(
				$this->_amount,
				$this->_currency
			);
		}
		return new Money(
			$this->_castToInteger($this->_amount / (100 + $this->_rate) * 100),
			$this->_currency
		);
	}

	// @return Money
	public function getGross() {
		if ($this->_type === 'gross') {
			return new Money(
				$this->_amount,
				$this->_currency
			);
		}
		return new Money(
			$this->_castToInteger($this->_amount / 100 * (100 + $this->_rate)),
			$this->_currency
		);
	}

	// @return Money
	public function getTax() {
		$result = $this->getGross();
		$result = $result->subtract($this->getNet());

		return $result;
	}

	/* Calculation */

	// @return Price
	public function negate() {
		return new Price(
			-1 * $this->_amount,
			$this->_currency,
			$this->_type,
			$this->_rate
		);
	}

	// @return Price
	public function multiply($factor) {
		return new Price(
			$this->_castToInteger($result->_amount * $factor),
			$result->_currency,
			$this->_type,
			$this->_rate
		);
	}

	// @return Price
	public function add(PriceInterface $value) {
		$us   = $this;
		$them = $value;

		if ($us->isZero()) {
			return clone $them;
		}
		if ($them->isZero()) {
			return clone $us;
		}

		$this->_assertSameCurrency($us, $them);
		$this->_assertSameRate($us, $them);

		$them = $this->_convertToCommonType($us, $them);

		return new Price(
			$us->getAmount() + $them->getAmount(),
			$us->getCurrency(),
			$us->getType(),
			$us->getRate()
		);
	}

	// @return Price
	public function subtract(PriceInterface $value) {
		$us   = clone $this;
		$them = clone $value;

		if ($us->isZero()) {
			return $them->negate();
		}
		if ($them->isZero()) {
			return clone $us;
		}

		$this->_assertSameCurrency($us, $them);
		$this->_assertSameRate($us, $them);

		$them = $this->_convertToCommonType($us, $them);

		return new Price(
			$us->getAmount() - $them->getAmount(),
			$us->getCurrency(),
			$us->getType(),
			$us->getRate()
		);
	}

	/* Comparison */

	public function isZero() {
		return $this->_amount === 0;
	}

	/* Helpers */

	protected function _assertSameCurrency($a, $b) {
		if ($a->getCurrency() != $b->getCurrency()) {
			throw new Exception('Calculation/comparison with differing currencies.');
		}
	}

	protected function _assertSameRate($a, $b) {
		if ($a->getRate() !== $b->getRate()) {
			throw new Exception('Calculation/comparison with differing rates.');
		}
	}

	// @return Price
	protected function _convertToCommonType($us, $them) {
		if ($them->getType() !== $us->getType()) {
			$method = 'get' . ucfirst($us->getType());
			return $them->$method();
		}
		return $them;
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