<?php

namespace Ximdex_Test;

class Sales
{
	protected $product;
	protected $cost;
	protected $quantity;
	public $category;
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

	public function getTotal()
	{
		// Check if have one or two operations
		if (strpos($this->benefits, "%") && strpos($this->benefits, "€")) {
			// Check witch one goes first
			if (strpos($this->benefits, "%") < strpos($this->benefits, "€")) {
				// Check if it adds or subtracts
				if ($this->benefits[0] != "-") {
					preg_match('/[+](.*?)%/', $this->benefits, $output_percent);
					$total_percent = $this->subtotal * ($output_percent[1] / 100);
					$total_percent = $this->subtotal + $total_percent;
					$benefit1 = str_replace($output_percent[0], "", $this->benefits);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)€/', $benefit1, $output_currency);
						$this->total = $total_percent + $output_currency[1];
					} else {
						preg_match('/[-](.*?)€/', $benefit1, $output_currency);
						$this->total = $total_percent - $output_currency[1];
					}
				} else {
					preg_match('/[-](.*?)%/', $this->benefits, $output_percent);
					$total_percent = $this->subtotal * ($output_percent[1] / 100);
					$total_percent = $this->subtotal - $total_percent;
					$benefit1 = str_replace($output_percent[0], "", $this->benefits);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)€/', $benefit1, $output_currency);
						$this->total = $total_percent + $output_currency[1];
					} else {
						preg_match('/[-](.*?)€/', $benefit1, $output_currency);
						$this->total = $total_percent - $output_currency[1];
					}
				}
			} else {
				if ($this->benefits[0] != "-") {
					preg_match('/[+](.*?)€/', $this->benefits, $output_currency);
					$total_currency = $this->subtotal + $output_currency[1];
					$benefit1 = str_replace($output_currency[0], "", $this->benefits);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)%/', $benefit1, $output_percent);
						$total_percent = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency + $total_percent;
					} else {
						preg_match('/[-](.*?)%/', $benefit1, $output_percent);
						$total_percent = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency - $total_percent;
					}
				} else {
					preg_match('/[-](.*?)€/', $this->benefits, $output_currency);
					$total_currency = $this->subtotal - $output_currency[1];
					$benefit1 = str_replace($output_currency[0], "", $this->benefits);
					if ($benefit1[0] != "-") {
						preg_match('/[+](.*?)%/', $benefit1, $output_percent);
						$total_percent = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency + $total_percent;
					} else {
						preg_match('/[-](.*?)%/', $benefit1, $output_percent);
						$total_percent = $total_currency * ($output_percent[1] / 100);
						$this->total = $total_currency - $total_percent;
					}
				}
			}
		} elseif (strpos($this->benefits, "%") && !strpos($this->benefits, "€")) {
			if (!strpos($this->benefits, "-")) {
				preg_match('/[+](.*?)%/', $this->benefits, $output_percent);
				$total_percent = $this->subtotal * ($output_percent[1] / 100);
				$this->total = $this->subtotal + $total_percent;
			} else {
				preg_match('/[-](.*?)%/', $this->benefits, $output_percent);
				$total_percent = $this->subtotal * ($output_percent[1] / 100);
				$this->total = $this->subtotal - $total_percent;
			}
		} elseif (!strpos($this->benefits, "%") && strpos($this->benefits, "€")) {
			if (!strpos($this->benefits, "-")) {
				preg_match('/[+](.*?)€/', $this->benefits, $output_currency);
				$this->total = $this->subtotal + $output_currency[1];
			} else {
				preg_match('/[-](.*?)€/', $this->benefits, $output_currency);
				$this->total = $this->subtotal - $output_currency[1];
			}
		}
	}
}
