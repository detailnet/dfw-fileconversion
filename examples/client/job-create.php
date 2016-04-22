<?php

use Detail\FileConversion\Client\FileConversionClient;

$config = require '../bootstrap.php';

$imageUrl = isset($_GET['image_url']) ? $_GET['image_url'] : null;

if (!$imageUrl) {
    throw new RuntimeException('Missing or invalid parameter "image_url"');
}

$imageSize = isset($_GET['image_size']) ? $_GET['image_size'] : 200;
$image = new SplFileInfo($imageUrl);
$imageName = $image->getBasename();

$getConfig = function($optionName) use ($config) {
    if (!isset($config[$optionName])) {
        throw new RuntimeException(sprintf('Missing configuration option "%s"', $optionName));
    }

    return $config[$optionName];
};

$client = FileConversionClient::factory($config);

/** @var \Detail\FileConversion\Client\Job\JobBuilder $jobBuilder */
$jobBuilder = $client->getJobBuilder();
$jobBuilder->setDefaultOption(
    'action.save',
    array(
        'type' => 's3',
        'params' => array(
            'bucket' => $getConfig('s3bucket'),
        ),
    )
);

$job = $jobBuilder->createJob()
    ->setSourceUrl($imageUrl)
    ->addAction(
        $jobBuilder->createAction()
            ->setName('thumbnail')
            ->setParams(
                array(
                    'size' => $imageSize,
                )
            )
            ->setSaveOptions(
                array(
                    'identifier' => $imageName,
                    'params' => array(
//                        'bucket' => $getConfig('s3bucket'),
                        'key' => $getConfig('s3path') . '/' . $imageName . '-' . $imageSize . '_dfw-fileconversion.jpg',
                    ),
                )
            )
    );

try {
    $job->addNotification(
        $jobBuilder->createNotification()
//            ->setType('webhook')
            ->setParams(
                array(
                    'url' => $getConfig('notification_url'),
                )
            )
    );
} catch (RuntimeException $e) {
    // Do nothing when no notification URL is provided (job will be created without notification)
}

$response = $client->submitJob($job);

var_dump($response->getResult());
