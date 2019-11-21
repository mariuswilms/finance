<?php
/**
 * Finance
 *
 * Copyright (c) 2014 Marius Wilms - All rights reserved.
 *
 * Use of this source code is governed by a BSD-style
 * license that can be found in the LICENSE file.
 */

namespace AD\Finance\Money;

use NumberFormatter;
use AD\Finance\Money\Monies;
use AD\Finance\Money\NullMoney;

class MoniesIntlFormatter {

	protected $_formatter;

	public function __construct($locale) {
		$this->_formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
	}

	public function format(Monies $value) {
		$results = [];

		foreach ($value->sum() as $currency => $money) {
			if ($money instanceof NullMoney) {
				continue;
			}
			$results[] = $this->_formatter->formatCurrency(
				$money->getAmount() / $money->getCurrency()->getSubUnit(),
				$money->getCurrency()->getCurrencyCode()
			);
		}
		if (!$results) {
			return 0;
		}
		return implode(' / ', $results);
	}
}

?>
