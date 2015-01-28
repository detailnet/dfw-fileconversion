<?php

use Detail\FileConversion\Client\FileConversionClient;

$config = require '../bootstrap.php';

$jobId = isset($_GET['job_id']) ? $_GET['job_id'] : null;

if (!$jobId) {
    throw new RuntimeException('Missing or invalid parameter "job_id"');
}

$client = FileConversionClient::factory($config);

$response = $client->fetchJob(array('job_id' => $jobId));

var_dump($response->getResult());
