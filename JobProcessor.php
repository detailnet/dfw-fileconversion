<?php

namespace Application\Job\Application\JobProcessing;

class JobProcessor implements
    JobProcessorInterface
{
    /**
     * @var Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @param Adapter\AdapterInterface $adapter
     */
    public function __construct(Adapter\AdapterInterface $adapter)
    {
        $this->setAdapter($adapter);
    }

    /**
     * @return Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param Adapter\AdapterInterface $adapter
     */
    public function setAdapter(Adapter\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Task\TaskInterface $task
     * @return Task\ResultInterface
     */
    public function process(Task\TaskInterface $task)
    {
        // TODO: Implement process() method.
    }

    /**
     * @param Task\TaskInterface $task
     * @return string Process identifier
     */
    public function startProcessing(Task\TaskInterface $task)
    {
        /** @todo This implementation is incomplete */

        $this->getAdapter()->supportsTask($task);
        // or
        $this->getAdapter()->supportsAction($task->getAction());
    }

    /**
     * @param array $result
     * @return Task\ResultInterface
     */
    public function endProcessing(array $result)
    {
        // TODO: Implement endProcessing() method.
    }
}
