<?php

require_once('genders/Female.php');
require_once('genders/Male.php');

class GenderFactory {

	private static $OPTIONS = array(
		'Female',
		'Male'
	);

	public static function options() {
		return array_keys(self::$OPTIONS);
	}

	public function random() {
		return self::create(self::$OPTIONS[rand(0, (count(self::$OPTIONS) - 1))]);
	}

	public static function proportional($employeeNumber, $totalEmployees) {
		$employeesOfType = ceil($totalEmployees / count(self::$OPTIONS)); // Not yet right; over-biases majority options when not an equal split (4, 4, 2 instead of 4, 3, 3)
		return self::create(self::$OPTIONS[intval(($employeeNumber - 1) / $employeesOfType)]);
	}

	private static function create($genderType) {
		return new $genderType();
	}
}
