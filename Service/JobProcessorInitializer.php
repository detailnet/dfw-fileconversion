<?php

namespace Application\Job\Application\JobProcessing\Service;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class JobProcessorInitializer implements
    InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof JobProcessorAwareInterface) {
            if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            /** @var \Application\Job\Application\JobProcessing\JobProcessor $jobProcessor */
            $jobProcessor = $serviceLocator->get(
                'Application\Job\Application\JobProcessing\JobProcessor'
            );

            $instance->setJobProcessor($jobProcessor);
        }
    }
}
