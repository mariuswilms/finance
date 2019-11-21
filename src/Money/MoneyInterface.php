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

interface MoneyInterface {

	public function getAmount();

	public function getCurrency();

	public function negate();

	public function multiply($factor);

	public function add(MoneyInterface $value);

	public function subtract(MoneyInterface $value);

	public function equals(MoneyInterface $value);

	public function greaterThan(MoneyInterface $value);

	public function lessThan(MoneyInterface $value);

	public function isZero();
}

?>
