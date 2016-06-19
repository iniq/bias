<?php

require_once('Gender.php');

class Employee {

	private $performanceScore;
	private $gender;
	private $initialEmployee = false;

	public function __construct($gender = null, $initialEmployee = false) {
		if (is_null($gender)) {
			$gender = new Gender();
		}

		$this->gender = $gender;
		$this->generatePerformanceScore();
		$this->initialEmployee = $initialEmployee;
	}

	public static function createProportionalEmployee($employeeNumber, $totalEmployees, $initialEmployee = false) {
		$gender = new Gender(Gender::selectProporitionalOption($employeeNumber, $totalEmployees));
		return new Employee($gender, $initialEmployee);
	}

	private function generatePerformanceScore() {
		$this->performanceScore = $this->gender->adjustScore(rand(0, 100));
	}

	public function getPerformanceScore() {
		return $this->performanceScore;
	}

	public function getGender() {
		return $this->gender->getValue();
	}

	public function isGender($gender) {
		return ($this->gender == $gender);
	}

	public function isOriginalEmployee() {
		return $this->initialEmployee;
	}
}
