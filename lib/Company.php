<?php

require_once('CorporateLevel.php');

class Company {

	private $levels;

	public function __construct() {
		$levelPositions = [500, 350, 200, 150, 100, 75, 40, 10];
		$this->levels = [];

		foreach ($levelPositions as $levelIndex => $employeeCount) {
			$this->levels[] = new CorporateLevel(($levelIndex + 1), $employeeCount);
		}
	}

	public function processAttrition($attritionRate = 0.15) {
		foreach ($this->levels as $levelIndex => $currentLevel) {
			$currentLevel->processAttrition($attritionRate);
		}
	}

	public function promoteToFill() {
		for ($i = (count($this->levels) - 1); $i > 0; $i--) {
			$currentLevel = $this->levels[$i];
			$currentLevel->promoteFrom($this->levels[($i - 1)]);
		}

		$bottomLevel = $this->levels[0];
		$bottomLevel->hireToFill();
	}

	public function hasOriginalEmployees() {
		foreach ($this->levels as $currentLevel) {
			if ($currentLevel->hasOriginalEmployees()) {
				return true;
			}
		}

		return false;
	}

	public function toString() {
		$outString = '';

		foreach ($this->levels as $currentLevel) {
			$outString .= $currentLevel->toString();
		}

		return $outString;
	}

	public function toJSON() {
		$genders = Gender::options();

		foreach ($genders as $genderString) {
			$data[$genderString] = [
				'key' => $genderString,
				'color' => Gender::$COLORS[$genderString],
				'values' => []
			];
		}

		for ($i = (count($this->levels) - 1); $i >= 0; $i--) {
			$currentLevel = $this->levels[$i];
			$label = 'Level '. $currentLevel->getLevelNumber();

			$levelSummary = $currentLevel->summary();

			foreach ($levelSummary as $genderLabel => $values) {
				$data[$genderLabel]['values'][] = [
					'label' => $label,
					'value' => $values['percentage']
				];
			}
		}

		// Doesn't like having the key there for gender
		$returnData = [];
		foreach ($genders as $genderString) {
			$returnData[] = $data[$genderString];
		}

		return json_encode($returnData);
	}

	public function printLevel($levelNumber) {
		$level = $this->levels[($levelNumber - 1)];

		$employees = $level->getEmployees();
		var_dump($employees);
	}
}
