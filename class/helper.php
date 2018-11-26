<?php
class helper {

	public function active($controller,$action){

	}

	public function product_price($priceFloat) {
		$symbol = ' VND';
		$symbol_thousand = '.';
		$decimal_place = 0;
		$price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
		return $price.$symbol;
	}

}