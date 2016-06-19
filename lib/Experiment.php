<?php

require_once('Company.php');

class Experiment {

	private $companies;
	private $jsonData;

	public function __construct($companyCount = 1) {
		srand();
		$this->jsonData = [];
		for ($i = 0; $i < $companyCount; $i++) {
			$company = new Company();
			$this->companies[] = $company;
			$this->jsonData[$i][0] = $company->generateJsonData();
		}
	}

	public function runExperiment($maxIterations = 100) {
		foreach ($this->companies as $companyIndex => $company) {
			$iterationNumber = 1;
			$this->jsonData[$companyIndex][0] = $company->generateJsonData();

			while ($iterationNumber <= $maxIterations && $company->hasOriginalEmployees()) {
				$this->runCycle($company);
				$this->jsonData[$companyIndex][$iterationNumber] = $company->generateJsonData();
				$iterationNumber++;
			}
		}
	}

	public function runCycle($company) {
		$company->processAttrition(0.15);
		$company->promoteToFill();
	}

	public function json($companyIndex = 0) {
		return json_encode($this->jsonData[$companyIndex]);
	}
}
