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

namespace AD\Finance\Money;

use NumberFormatter;
use AD\Finance\Money\MoneyInterface;
use AD\Finance\Money\NullMoney;

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