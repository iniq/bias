<?php

require_once('Employee.php');
require_once('Gender.php');

class CorporateLevel {

	private $levelNumber;
	private $employeeCount = 0;
	private $employees = [];
	private $genderCount = [];
	private $hasOriginalEmployees = false;

	public function __construct($levelNumber, $employeeCount) {
		$this->levelNumber = $levelNumber;
		$this->employeeCount = $employeeCount;
		$this->employees = [];
		$this->genderCount = array_flip(Gender::options());

		foreach ($this->genderCount as $index => $value) {
			$this->genderCount[$index] = 0;
		}

		$this->hireToFill($initialEmployees = true);
		$this->sortByPerformance();
	}

	private function addEmployee($newEmployee) {
		$this->genderCount[$newEmployee->getGender()]++;
		$this->employees[] = $newEmployee;

		$this->hasOriginalEmployees = $this->hasOriginalEmployees || $newEmployee->isOriginalEmployee();
	}

	private function removeEmployee($employeeIndex) {
		$employee = $this->employees[$employeeIndex];
		$this->genderCount[$employee->getGender()]--;
		unset($this->employees[$employeeIndex]);

		if ($employee->isOriginalEmployee()) {
			$this->verifyOriginalEmployees();
		}
	}

	public function getLevelNumber() {
		return $this->levelNumber;
	}

	public function getEmployees() {
		return $this->employees;
	}

	public function getEmployeeCount() {
		return $this->employeeCount;
	}

	public function getGenderCount() {
		return $this->genderCount;
	}

	private function sortByPerformance() {
		usort($this->employees, function ($a, $b) {
			$aScore = $a->getPerformanceScore();
			$bScore = $b->getPerformanceScore();

			if ($aScore == $bScore) {
				return 0;
			}

			return ($aScore < $bScore) ? 1 : -1;
		});
	}

	public function processAttrition($attritionRate = 0.15) {
		$terminatedEmployeeIndicies = (array)array_rand($this->employees, floor($this->employeeCount * $attritionRate));
		foreach ($terminatedEmployeeIndicies as $terminatedEmployeeIndex) {
			$this->removeEmployee($terminatedEmployeeIndex);
		}
	}

	public function promoteFrom($promotableLevel) {
		$promotions = $promotableLevel->releaseTopEmployees($this->numberOfEmployeesMissing());

		foreach ($promotions as $promotedEmployee) {
			$this->addEmployee($promotedEmployee);
		}
	}

	public function releaseTopEmployees($numberToRelease) {
		$this->sortByPerformance();
		$topEmployees = [];
		for ($i = 0; $i < $numberToRelease; $i++) {
			$topEmployees[] = $this->employees[$i];
			$this->removeEmployee($i);
		}

		return $topEmployees;
	}

	public function hireToFill($initialEmployees = false) {
		$countToHire = $this->numberOfEmployeesMissing();
		$halfEmployees = floor($countToHire / 2);

		for ($i = 0; $i < $countToHire; $i++) {
			$newEmployee = Employee::createProportionalEmployee(($i + 1), $countToHire, $initialEmployees);
			$this->addEmployee($newEmployee);
		}
	}

	public function hasOriginalEmployees() {
		return $this->hasOriginalEmployees;
	}

	private function verifyOriginalEmployees() {
		foreach ($this->employees as $employee) {
			if ($employee->isOriginalEmployee()) {
				$this->hasOriginalEmployees = true;
				return true;
			}
		}

		$this->hasOriginalEmployees = false;
		return false;
	}

	private function numberOfEmployeesMissing() {
		return ($this->employeeCount - count($this->employees));
	}

	public function toString() {
		$genderStrings = [];

		$levelSummary = $this->summary();
		foreach ($levelSummary as $genderLabel => $values) {
			$genderStrings[] = sprintf('%s %d, [%f percent]', $genderLabel, $values['count'], $values['percentage']);
		}

		return sprintf("Company level %d: %s\n", $this->levelNumber, implode(', ', $genderStrings));
	}

	public function summary() {
		$summary = [];

		foreach ($this->genderCount as $genderValue => $genderHeadcount) {
			$summary[$genderValue] = [
				'count' => $genderHeadcount,
				'percentage' => (($genderHeadcount / $this->employeeCount) * 100)
			];
		}

		return $summary;
	}
}
