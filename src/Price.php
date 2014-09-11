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
use Finance\NullPrice;
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

	protected $_taxRate;

	public function __construct($value, $currency, $type, $taxRate = null) {
		$this->_amount = $value;

		if (!is_string($currency) && !is_object($currency)) {
			throw new InvalidArgumentException('Price currency must be of type string or object.');
		}
		$this->_currency = is_object($currency) ? $currency : new Currency($currency);

		if ($type !== 'net' && $type !== 'gross') {
			throw new InvalidArgumentException('Price type must be either `net` or `gross`.');
		}
		$this->_type = $type;

		if ($taxRate !== null && !is_integer($taxRate)) {
			throw new InvalidArgumentException('Price tax rate must be of type integer.');
		}
		$this->_taxRate = $taxRate;
	}

	public function getMoney() {
		return new Money((integer) $this->_amount, $this->_currency);
	}

	public function getAmount($type = null) {
		if ($type === 'net') {
			return $this->getNet()->getAmount();
		} elseif ($type === 'gross') {
			return $this->getGross()->getAmount();
		}
		return $this->_amount;
	}

	public function getCurrency() {
		return $this->_currency;
	}

	public function getType() {
		return $this->_type;
	}

	public function getTaxRate() {
		return $this->_taxRate;
	}

	public function removeTaxRate() {
		return new Price(
			$this->_amount,
			$this->_currency,
			$this->_type,
			null
		);
	}

	public function getNet() {
		if ($this->_type === 'net') {
			return $this;
		}
		if (!$this->_taxRate) {
			throw new Exception('Cannot calculate net from gross price without tax rate.');
		}
		return new Price(
			($this->_amount / (100 + $this->_taxRate) * 100),
			$this->_currency,
			'net',
			$this->_taxRate
		);
	}

	public function getGross() {
		if ($this->_type === 'gross') {
			return $this;
		}
		if (!$this->_taxRate) {
			throw new Exception('Cannot calculate gross from net price without tax rate.');
		}
		return new Price(
			($this->_amount / 100 * (100 + $this->_taxRate)),
			$this->_currency,
			'gross',
			$this->_taxRate
		);
	}

	public function getTax() {
		$result = $this->getGross()->getMoney();
		$result = $result->subtract($this->getNet()->getMoney());

		return $result;
	}

	public function multiply($factor) {
		return new Price(
			$this->_amount * $factor,
			$this->_currency,
			$this->_type,
			$this->_taxRate
		);
	}

	public function add(PriceInterface $value) {
		$us   = clone $this;
		$them = clone $value;

		if ($them instanceof NullPrice) {
			return $us;
		}

		if ($them->getCurrency() != $us->getCurrency()) {
			throw new Exception('Cannot add prices with different currencies.');
		}
		if ($them->getTaxRate() !== $us->getTaxRate()) {
			throw new Exception('Cannot add prices with different tax rates.');
		}

		if ($them->getType() !== $us->getType()) {
			$method = 'get' . ucfirst($us->getType());
			$them = $them->$method();
		}
		return new Price(
			$us->getAmount() + $them->getAmount(),
			$us->getCurrency(),
			$us->getType(),
			$us->getTaxRate()
		);
	}

	public function subtract(PriceInterface $value) {
		$us   = clone $this;
		$them = clone $value;

		if ($them instanceof NullPrice) {
			return $us->negate();
		}

		if ($them->getCurrency() != $us->getCurrency()) {
			throw new Exception('Cannot subtract prices with different currencies.');
		}
		if ($them->getTaxRate() !== $us->getTaxRate()) {
			throw new Exception('Cannot subtract prices with different tax rates.');
		}

		if ($them->getType() !== $us->getType()) {
			$method = 'get' . ucfirst($us->getType());
			$them = $them->$method();
		}
		return new Price(
			$us->getAmount() - $them->getAmount(),
			$us->getCurrency(),
			$us->getType(),
			$us->getTaxRate()
		);
	}

	public function greaterThan(PriceInterface $value) {
		return $this->getAmount($value->getType()) > $value->getAmount();
	}

	public function isZero() {
		return $this->_amount === 0;
	}

	public function negate() {
		return new Price(
			-1 * $this->_amount,
			$this->_currency,
			$this->_type,
			$this->_taxRate
		);
	}

	public function removeTaxRate() {
		return new Price(
			$this->_amount,
			$this->_currency,
			$this->_type,
			null
		);
	}
}

?>