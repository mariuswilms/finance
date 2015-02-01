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