<?php

require_once('Company.php');

class Experiment {

	private $companies;
	private $json;

	public function __construct($companyCount = 1) {
		srand();
		$this->json = [];
		for ($i = 0; $i < $companyCount; $i++) {
			$company = new Company();
			$this->companies[] = $company;
			$this->json[$i][0] = $company->toJSON();
		}
	}

	public function runExperiment($maxIterations = 100) {
		foreach ($this->companies as $companyIndex => $company) {
			echo '<h1>Company #'. ($companyIndex + 1) .'</h1>';
			$iterationNumber = 1;
			while ($iterationNumber <= $maxIterations && $company->hasOriginalEmployees()) {
				// echo '<h2>Running Cycle '. $iterationNumber .'</h2>';
				$this->runCycle($company);
				$this->json[$companyIndex][$iterationNumber] = $company->toJSON();
				$iterationNumber++;
			}
		}
	}

	public function runCycle($company) {
		$company->processAttrition(0.15);
		$company->promoteToFill();

// echo nl2br($company->toString());
// $company->printLevel(8);
	}

	public function json($companyIndex) {
		return $this->json[$companyIndex];
	}
}
