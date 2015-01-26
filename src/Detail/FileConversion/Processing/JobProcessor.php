<?php

namespace Detail\FileConversion\Processing;

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
        throw new Exception\RuntimeException('Not yet implemented');
    }

    /**
     * @param Task\TaskInterface $task
     * @return string Process identifier
     */
    public function startProcessing(Task\TaskInterface $task)
    {
        /** @todo This implementation is incomplete */

        $adapter = $this->getAdapter();

//        $adapter->supportsTask($task);
//        // or
//        $adapter->supportsAction($task->getAction());

        $processId = $adapter->startProcessing($task);

        $task->setProcessId($processId);

        return $processId;
    }

    /**
     * @param Task\TaskInterface $task
     * @return Task\ResultInterface|null
     */
    public function checkProcessing(Task\TaskInterface $task)
    {
        $adapter = $this->getAdapter();
        $result = $adapter->checkProcessing($task);

        return $result;
    }

    /**
     * @param Task\TaskInterface $task
     * @param array $data
     * @return Task\ResultInterface
     */
    public function endProcessing(Task\TaskInterface $task, array $data)
    {
        $adapter = $this->getAdapter();
        $result = $adapter->endProcessing($task, $data);

        return $result;
    }
}
