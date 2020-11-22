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

interface PriceInterface {

	public function getAmount();

	public function getCurrency();

	public function getType();

	public function getRate();

	public function getNet();

	public function getGross();

	public function getTax();

	public function multiply($factor);

	public function add(PriceInterface $value);

	public function subtract(PriceInterface $value);

	public function isZero();

	public function negate();
}

?>
