<?php

namespace Ximdex_Test;

// USE in CLI: php test.php ventas.php precios.json

include('lib/classes/class.Sales.php');

// GET Arguments
if (isset($argc)) {
	for ($i = 0; $i < $argc; $i++) {

		$file_parts = pathinfo($argv[$i]);

		switch ($file_parts['extension']) {
			case "csv":
				$csv_file = $argv[$i];
				break;

			case "json":
				$json_file = $argv[$i];
				break;

			case NULL: // Handle no file extension or unknown
				echo "Unknown file extension \n";
				exit;
		}
	}
} else {
	echo "argc and argv disabled \n";
}


// Check files first
if (!file_exists($csv_file)) {
	echo "File " . $csv_file . " not found \n";
	exit;
}
if (!file_exists($json_file)) {
	echo "File " . $json_file . " not found \n";
	exit;
}


// Get CSV data readable
$attributes = $csv_array_objects = $fields = array();
$handle = @fopen($csv_file, "r");
if ($handle) {
	while (($row = fgetcsv($handle, 1000, ";")) !== false) {
		if (empty($fields)) {
			$fields = $row;
			continue;
		}
		foreach ($row as $k => $value) {
			$attributes[$fields[$k]] = $value;
		}

		$csv_object = new Sales();
		$csv_object->setAttributes($attributes);
		$csv_array_objects[] = $csv_object;
	}
	if (!feof($handle)) {
		echo "Error: unexpected fgets() fail \n";
	}
	fclose($handle);
}


// Get JSON data readable
$json_string = file_get_contents($json_file);
$json_array  = json_decode($json_string, true);


// Insert benefits into objs
$count_objects = count($csv_array_objects);
for ($i = 0; $i < $count_objects; $i++) {
	$sale_category = $csv_array_objects[$i]->category;
	$json_category = $json_array['categories'];
	if (array_key_exists($sale_category, $json_category)) {
		$csv_array_objects[$i]->benefits_sale = $json_category[$sale_category];
	} else {
		$csv_array_objects[$i]->benefits_sale = $json_category['*'];
	}
	$csv_array_objects[$i]->getSubtotal();
	$csv_array_objects[$i]->getTotal();

	// Save category totals and sum equals
	if (isset($category_total[$csv_array_objects[$i]->category])) {
		$category_total[$csv_array_objects[$i]->category] += $csv_array_objects[$i]->benefit;
	} else {
		$category_total[$csv_array_objects[$i]->category] = $csv_array_objects[$i]->benefit;
	}
}


// Return benefits to CLI
foreach ($category_total as $key => $value) {
	echo $key . ": " . number_format((float) $value, 2, ".", "") . "\n\n";
}
