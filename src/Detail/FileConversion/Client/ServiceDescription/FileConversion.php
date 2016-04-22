<?php

use Detail\FileConversion\Client\Response;

return array(
    'name'        => 'DETAIL Web Services - File Conversion Service',
    'operations'  => array(
        'listJobs' => array(
            'httpMethod'       => 'GET',
            'uri'              => 'jobs',
            'summary'          => 'List jobs',
//            'documentationUrl' => 'http://tbd',
            'parameters'       => array(
                'page' => array(
                    'description' => 'The number of the page',
                    'location'    => 'query',
                    'type'        => 'integer',
                    'required'    => false,
                ),
                'page_size' => array(
                    'description' => 'The number of jobs to list on a page',
                    'location'    => 'query',
                    'type'        => 'integer',
                    'required'    => false,
                ),
            ),
            'responseClass' => Response\JobList::CLASS,
        ),
        'fetchJob' => array(
            'httpMethod'       => 'GET',
            'uri'              => 'jobs/{job_id}',
            'summary'          => 'Fetch a job',
//            'documentationUrl' => 'http://tbd',
            'parameters'       => array(
                'job_id' => array(
                    'description' => 'The ID of the job you wish to fetch',
                    'location'    => 'uri',
                    'type'        => 'string',
                    'required'    => true,
                ),
            ),
            'responseClass' => Response\Job::CLASS,
        ),
        'submitJob' => array(
            'httpMethod'       => 'POST',
            'uri'              => 'jobs',
            'summary'          => 'Submit a new job',
//            'documentationUrl' => 'http://tbd',
            'parameters'       => array(
                'source_url' => array(
                    'description' => 'The location of the file you wish to convert',
                    'location'    => 'json',
                    'type'        => 'string',
                    'required'    => true,
                ),
                /** @todo Define actions properly and remove "additionalParameters" as catch-all... */
//                'actions' => array(
//                    'description' => 'One or more operations you want performed on the source image',
//                    'location'    => 'json',
//                    'type'        => 'array',
//                    'required'    => true,
//                ),
            ),
            'additionalParameters' => array(
                'location' => 'json',
            ),
            'responseClass' => Response\Job::CLASS,
        ),
    ),
);
