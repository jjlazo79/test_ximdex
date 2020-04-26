<?php

namespace Ximdex_Test;

class Sales
{
	protected $product;
	public $category;
	protected $cost;
	protected $quantity;
	public $benefits;
	public $subtotal;
	public $total;

	public function setAttributes($attributes)
	{
		$this->product	= isset($attributes['PRODUCT']) ? $attributes['PRODUCT'] : null;
		$this->category = isset($attributes['CATEGORY']) ? $attributes['CATEGORY'] : null;
		$this->cost		= isset($attributes['COST']) ? $attributes['COST'] : null;
		$this->quantity = isset($attributes['QUANTITY']) ? str_replace(".", "", $attributes['QUANTITY']) : null;
		$this->benefits = isset($attributes['BENEFITS']) ? $attributes['BENEFITS'] : null;
	}

	public function getSubtotal()
	{
		$fmt = numfmt_create('es_ES', \NumberFormatter::CURRENCY);
		$num = str_replace("€", "\xc2\xa0€", $this->cost);
		$cost_format = $fmt->parseCurrency($num, $curr);

		$this->subtotal = $cost_format * $this->quantity;

		return $this->subtotal;
	}

	private function parseBenefits()
	{
		$parsed_currency_plus = $this->getStringBetween($this->benefits, '+', '€');
		$parsed_currency_minus = $this->getStringBetween($this->benefits, '-', '€');
		$parsed_percent_plus = $this->getStringBetween($this->benefits, '+', '%');
		$parsed_percent_minus = $this->getStringBetween($this->benefits, '-', '%');

		// Sumar un porcentaje ($percent) a un precio
		$price += ($price * $percent / 100);
		// Restar un porcentaje ($percent) a un precio
		$price -= ($price * $percent / 100);
		return $output;
	}

	private function getStringBetween($string, $start, $end, $inclusive = false)
	{
		$string = " " . $string;
		$ini = strpos($string, $start);
		if ($ini == 0) return "";
		if (!$inclusive) $ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		if ($inclusive) $len += strlen($end);
		return substr($string, $ini, $len);
	}
}
