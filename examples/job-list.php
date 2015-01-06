<?php

use Detail\FileConversion\Client\FileConversionClient;

$config = require 'bootstrap.php';
$params = array();

if (isset($_GET['page'])) {
    $params['page'] = (int) $_GET['page'];
}

if (isset($_GET['page_size'])) {
    $params['page_size'] = (int) $_GET['page_size'];
}

$client = FileConversionClient::factory($config);

$response = $client->listJobs($params);

if ($response->isError()) {
    var_dump($response->getError());
} else {
    var_dump($response->getResult());
}
