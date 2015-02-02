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