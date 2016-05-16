<?php

require_once('CorporateLevel.php');

class Company {

	private $levels;

	public function __construct() {
		$levelPositions = [500, 350, 200, 150, 100, 75, 40, 10];
		$this->levels = [];

		foreach ($levelPositions as $employeeCount) {
			$this->levels[] = new CorporateLevel($employeeCount);
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

		foreach ($this->levels as $levelIndex => $currentLevel) {
			$genderStrings = [];
			$genderCount = $currentLevel->getGenderCount();
			$levelHeadcount = $currentLevel->getEmployeeCount();

			foreach ($genderCount as $genderValue => $genderHeadcount) {
				$genderStrings[] = sprintf('%s %d, [%f percent]', $genderValue, $genderHeadcount, ($genderHeadcount / $levelHeadcount) * 100);
			}

			$outString .= sprintf("Company level %d: %s\n", ($levelIndex + 1), implode(', ', $genderStrings));
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

			$genderCount = $currentLevel->getGenderCount();
			$levelHeadcount = $currentLevel->getEmployeeCount();

			foreach ($genderCount as $genderString => $genderHeadcount) {
				$data[$genderString]['values'][] = [
					'label' => 'Level '. ($i + 1),
					'value' => (($genderHeadcount / $levelHeadcount) * 100)
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
