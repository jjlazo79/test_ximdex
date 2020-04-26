<?php

namespace Ximdex_Test;

class Sales
{
	protected $product;
	protected $cost;
	protected $quantity;
	public $category;
	public $benefits_sale;
	public $benefit;
	public $subtotal;
	public $total;

	public function setAttributes($attributes)
	{
		$this->product	= isset($attributes['PRODUCT']) ? $attributes['PRODUCT'] : null;
		$this->category = isset($attributes['CATEGORY']) ? $attributes['CATEGORY'] : null;
		$this->cost		= isset($attributes['COST']) ? $attributes['COST'] : null;
		$this->quantity = isset($attributes['QUANTITY']) ? str_replace(".", "", $attributes['QUANTITY']) : null;
		$this->benefits_sale = isset($attributes['BENEFITS']) ? $attributes['BENEFITS'] : null;
	}

	public function getSubtotal()
	{
		$fmt = numfmt_create('es_ES', \NumberFormatter::CURRENCY);
		$num = str_replace("€", "\xc2\xa0€", $this->cost);
		$cost_format = $fmt->parseCurrency($num, $curr);

		$this->subtotal = $cost_format * $this->quantity;

		return $this->subtotal;
	}

	public function getTotal()
	{
		// Check if have one or two operations
		if (strpos($this->benefits_sale, "%") && strpos($this->benefits_sale, "€")) {
			// Check witch one goes first
			if (strpos($this->benefits_sale, "%") < strpos($this->benefits_sale, "€")) {
				// Check if it adds or subtracts
				if ($this->benefits_sale[0] != "-") {
					preg_match('/[+](.*?)%/', $this->benefits_sale, $output_percent);
					$total_percent = $this->subtotal * ($output_percent[1] / 100);
					$benefit = $this->subtotal + $total_percent;
					$benefit1 = str_replace($output_percent[0], "", $this->benefits_sale);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)€/', $benefit1, $output_currency);
						$this->benefit = $total_percent + $output_currency[1];
						$this->total = $benefit + $output_currency[1];
					} else {
						preg_match('/[-](.*?)€/', $benefit1, $output_currency);
						$this->benefit = $total_percent - $output_currency[1];
						$this->total = $benefit - $output_currency[1];
					}
				} else {
					preg_match('/[-](.*?)%/', $this->benefits_sale, $output_percent);
					$total_percent = $this->subtotal * ($output_percent[1] / 100);
					$benefit = $this->subtotal - $total_percent;
					$benefit1 = str_replace($output_percent[0], "", $this->benefits_sale);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)€/', $benefit1, $output_currency);
						$this->benefit = $total_percent + $output_currency[1];
						$this->total = $benefit + $output_currency[1];
					} else {
						preg_match('/[-](.*?)€/', $benefit1, $output_currency);
						$this->benefit = $total_percent + $output_currency[1];
						$this->total = $benefit - $output_currency[1];
					}
				}
			} else {
				if ($this->benefits_sale[0] != "-") {
					preg_match('/[+](.*?)€/', $this->benefits_sale, $output_currency);
					$total_currency = $this->subtotal + $output_currency[1];
					$benefit1 = str_replace($output_currency[0], "", $this->benefits_sale);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)%/', $benefit1, $output_percent);
						$this->benefit = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency + $this->benefit;
					} else {
						preg_match('/[-](.*?)%/', $benefit1, $output_percent);
						$this->benefit = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency - $this->benefit;
					}
				} else {
					preg_match('/[-](.*?)€/', $this->benefits_sale, $output_currency);
					$total_currency = $this->subtotal - $output_currency[1];
					$benefit1 = str_replace($output_currency[0], "", $this->benefits_sale);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)%/', $benefit1, $output_percent);
						$this->benefit = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency + $this->benefit;
					} else {
						preg_match('/[-](.*?)%/', $benefit1, $output_percent);
						$this->benefit = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency - $this->benefit;
					}
				}
			}
		} elseif (strpos($this->benefits_sale, "%") && !strpos($this->benefits_sale, "€")) {
			if (!strpos($this->benefits_sale, "-")) {
				preg_match('/[+](.*?)%/', $this->benefits_sale, $output_percent);
				$this->benefit = $this->subtotal * ($output_percent[1] / 100);
				$this->total = $this->subtotal + $this->benefit;
			} else {
				preg_match('/[-](.*?)%/', $this->benefits_sale, $output_percent);
				$this->benefit = $this->subtotal * ($output_percent[1] / 100);
				$this->total = $this->subtotal - $this->benefit;
			}
		} elseif (!strpos($this->benefits_sale, "%") && strpos($this->benefits_sale, "€")) {
			if (!strpos($this->benefits_sale, "-")) {
				preg_match('/[+](.*?)€/', $this->benefits_sale, $output_currency);
				$this->benefit = $output_currency[1] * $this->quantity;
				$this->total = $output_currency[1];
			} else {
				preg_match('/[-](.*?)€/', $this->benefits_sale, $output_currency);
				$this->benefit = $output_currency[1] * $this->quantity;
				$this->total = $this->subtotal - $output_currency[1];
			}
		}
		return $this->total;
	}
}
