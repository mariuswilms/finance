<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace Finance\Money;

use NumberFormatter;
use Finance\Money\MoneyInterface;
use Finance\Money\NullMoney;

class MoneyIntlFormatter {

	protected $_formatter;

	public function __construct($locale) {
		$this->_formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
	}

	public function format(MoneyInterface $value) {
		if ($value instanceof NullMoney) {
			return 0;
		}
		return $this->_formatter->formatCurrency(
			$value->getAmount() / $value->getCurrency()->getSubUnit(),
			$value->getCurrency()->getCurrencyCode()
		);
	}
}

?>
