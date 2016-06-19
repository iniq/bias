<?php
header('Content-Type: application/json; Charset=UTF-8');

require_once('lib/Experiment.php');

$experiment = new Experiment(1);
$experiment->runExperiment();

echo $experiment->json();
