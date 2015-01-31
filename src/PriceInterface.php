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