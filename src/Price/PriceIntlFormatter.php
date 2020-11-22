<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace Finance\Price;

use NumberFormatter;
use Finance\Price\PriceInterface;

class PriceIntlFormatter {

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
