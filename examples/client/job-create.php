<?php

use Detail\FileConversion\Client\FileConversionClient;

$config = require '../bootstrap.php';

$imageUrl = isset($_GET['imageUrl']) ? $_GET['imageUrl'] : null;

if (!$imageUrl) {
    throw new RuntimeException('Missing or invalid parameter "imageUrl"');
}

$imageSize = isset($_GET['imageSize']) ? $_GET['imageSize'] : 200;
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
            ->setName('resize_to_fit')
            ->setParams(
                array(
                    'width' => $imageSize,
                    'height' => $imageSize,
//                    'only_shrink_larger' => true, // Don't upscale image
                )
            )
            ->setSaveOptions(
                array(
                    'identifier' => $imageName,
                    'params' => array(
//                        'bucket' => $getConfig('s3bucket'),
                        'key' => $getConfig('s3path') . '/' . $imageName . '-' . $imageSize . '_dfw-fileconversion.jpg',
                    ),
                ),
                true // Merge with defaults
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
