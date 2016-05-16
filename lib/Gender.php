<?php

class Gender {
	private $value = null;

	const FEMALE = 'female';
	const MALE = 'male';

	private static $ADJUSTMENTS = array(
		self::MALE => 1,
		self::FEMALE => 0
	);

	public static $COLORS = array(
		self::MALE => '#0000ff',
		self::FEMALE => '#ff69b4'
	);

	public function __construct($value = null) {
		if (is_null($value)) {
			$value = self::selectRandomOption();
		}

		$this->value = $value;
	}

	public static function options() {
		return array_keys(self::$ADJUSTMENTS);
	}

	public static function selectRandomOption() {
		$options = self::options();
		return $options[rand(0, (count($options) - 1))];
	}

	public static function selectProporitionalOption($employeeNumber, $totalEmployees) {
		$options = self::options();
		$employeesOfType = ceil($totalEmployees / count($options)); // Not yet right; over-biases majority options when not an equal split (4, 4, 2 instead of 4, 3, 3)

		return $options[intval(($employeeNumber - 1) / $employeesOfType)];
	}

	public function getValue() {
		return $this->value;
	}

	public function getScoreAdjustment() {
		return self::$ADJUSTMENTS[$this->value];
	}
}
