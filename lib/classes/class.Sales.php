<?php

namespace Ximdex_Test;

class Sales
{
	protected $product;
	protected $category;
	protected $cost;
	protected $quantity;
	protected $benefits;
	public $total;

	public function setAttributes($attributes)
	{
		$this->product	= isset($attributes['PRODUCT'])		? $attributes['PRODUCT']	: null;
		$this->category = isset($attributes['CATEGORY'])	? $attributes['CATEGORY']	: null;
		$this->cost		= isset($attributes['COST'])		? $attributes['COST']		: null;
		$this->quantity = isset($attributes['QUANTITY'])	? $attributes['QUANTITY']	: null;
		$this->benefits = isset($attributes['BENEFITS'])	? $attributes['BENEFITS']	: null;
	}
}
