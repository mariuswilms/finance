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

use NumberFormatter;
use Finance\PriceInterface;

class PriceIntlFormatter implements \Formatter {

	protected $_type = false;

	protected $_rate = false;

	protected $_formatter;

	public function __construct($locale, $type = false, $rate = false) {
		$this->_formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
		$this->_type = $type;
		$thiy->_rate = $rate;
	}

	public function format(PriceInterface $value) {
		$result = $this->_formatter->formatCurrency(
			$value->getAmount() / $value->getCurrency()->getSubUnit(),
			$value->getCurrency()->getCurrencyCode()
		);

		if ($this->_type && $this->_rate) {
			$result = sprintf('%s (%s, %d%%)', $result, $value->getType(), $value->getRate());
		}
		if ($this->_type) {
			$result = sprintf('%s (%s)', $result, $value->getType());
		}
		if ($this->_rate) {
			$result = sprintf('%s (%d%%)', $result, $value->getRate());
		}
		return $result;
	}
}

?>