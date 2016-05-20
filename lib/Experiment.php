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
			$iterationNumber = 1;
			$this->json[$companyIndex][0] = $company->toJSON();

			while ($iterationNumber <= $maxIterations && $company->hasOriginalEmployees()) {
				$this->runCycle($company);
				$this->json[$companyIndex][$iterationNumber] = $company->toJSON();
				$iterationNumber++;
			}
		}
	}

	public function runCycle($company) {
		$company->processAttrition(0.15);
		$company->promoteToFill();
	}

	public function json($companyIndex) {
		return $this->json[$companyIndex];
	}
}
