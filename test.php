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
				// echo "CSV file path:" . $csv_file . "\n";
				break;

			case "json":
				$json_file = $argv[$i];
				// echo "JSON file path:" . $json_file . "\n";
				break;

			case "": // Handle file extension for files ending in '.'
			case NULL: // Handle no file extension
				break;
		}
	}
} else {
	echo "argc and argv disabled\n";
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
		echo "Error: unexpected fgets() fail\n";
	}
	fclose($handle);
}

var_dump($csv_array_objects);
var_dump($csv_array_objects[0]->total);


// Get JSON data readable


// Calculate benefits


// Return benefits
