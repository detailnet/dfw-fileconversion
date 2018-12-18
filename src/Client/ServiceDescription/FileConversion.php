<?php

use Detail\FileConversion\Client\Response;

return [
    'name'        => 'DETAIL Web Services - File Conversion Service',
    'operations'  => [
        'listJobs' => [
            'httpMethod'       => 'GET',
            'uri'              => 'jobs',
            'summary'          => 'List jobs',
//            'documentationUrl' => 'http://tbd',
            'parameters'       => [
                'page' => [
                    'description' => 'The number of the page',
                    'location'    => 'query',
                    'type'        => 'integer',
                    'required'    => false,
                ],
                'page_size' => [
                    'description' => 'The number of jobs to list on a page',
                    'location'    => 'query',
                    'type'        => 'integer',
                    'required'    => false,
                ],
            ],
            'responseModel' => Response\JobList::CLASS,
        ],
        'fetchJob' => [
            'httpMethod'       => 'GET',
            'uri'              => 'jobs/{job_id}',
            'summary'          => 'Fetch a job',
//            'documentationUrl' => 'http://tbd',
            'parameters'       => [
                'job_id' => [
                    'description' => 'The ID of the job you wish to fetch',
                    'location'    => 'uri',
                    'type'        => 'string',
                    'required'    => true,
                ],
            ],
            'responseModel' => Response\Job::CLASS,
        ],
        'submitJob' => [
            'httpMethod'       => 'POST',
            'uri'              => 'jobs',
            'summary'          => 'Submit a new job',
//            'documentationUrl' => 'http://tbd',
            'parameters'       => [
                'source_url' => [
                    'description' => 'The location of the file you wish to convert',
                    'location'    => 'json',
                    'type'        => 'string',
                    'required'    => true,
                ],
                /** @todo Define actions properly and remove "additionalParameters" as catch-all... */
//                'actions' => array(
//                    'description' => 'One or more operations you want performed on the source image',
//                    'location'    => 'json',
//                    'type'        => 'array',
//                    'required'    => true,
//                ),
            ],
            'additionalParameters' => [
                'location' => 'json',
            ],
            'responseModel' => Response\Job::CLASS,
        ],
    ],
];
