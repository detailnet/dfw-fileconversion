<?php

return array(
    'name'        => 'DETAIL Web Services - File Conversion Service',
    'description' => 'Image processing in the cloud',
    'operations'  => array(
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
            'responseClass' => 'Detail\FileConversion\Response\Job',
        ),
        'createJob' => array(
            'httpMethod'       => 'POST',
            'uri'              => 'job',
            'summary'          => 'Create a new job',
//            'documentationUrl' => 'http://tbd',
            'parameters'       => array(
                'src' => array(
                    'description' => 'The location of the image you wish to process',
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
            'responseClass' => 'Detail\FileConversion\Response\Job',
        ),
    ),
);
