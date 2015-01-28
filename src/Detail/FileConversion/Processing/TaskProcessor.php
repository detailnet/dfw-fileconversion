<?php

namespace Detail\FileConversion\Processing;

class TaskProcessor implements
    TaskProcessorInterface
{
    /**
     * @var AdapterManagerInterface
     */
    protected $adapters;

    /**
     * @var string
     */
    protected $defaultAdapter;

    /**
     * @param AdapterManagerInterface $adapters
     * @param string $defaultAdapter
     */
    public function __construct(AdapterManagerInterface $adapters, $defaultAdapter = null)
    {
        $this->setAdapters($adapters);

        if ($defaultAdapter !== null) {
            $this->setDefaultAdapter($defaultAdapter);
        }
    }

    /**
     * @return AdapterManagerInterface
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * @param AdapterManagerInterface $adapters
     */
    public function setAdapters(AdapterManagerInterface $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * @return string
     */
    public function getDefaultAdapter()
    {
        return $this->defaultAdapter;
    }

    /**
     * @param string $defaultAdapter
     */
    public function setDefaultAdapter($defaultAdapter)
    {
        $this->defaultAdapter = $defaultAdapter;
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

        $adapter = $this->getAdapter($task);

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
        $adapter = $this->getAdapter($task);
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
        $adapter = $this->getAdapter($task);
        $result = $adapter->endProcessing($task, $data);

        return $result;
    }

    /**
     * @param Task\TaskInterface $task
     * @return Adapter\AdapterInterface
     */
    protected function getAdapter(Task\TaskInterface $task)
    {
        if ($task->getAdapter() !== null) {
            $adapter = $task->getAdapter();
        } elseif ($this->getDefaultAdapter() !== null) {
            $adapter = $this->getDefaultAdapter();
        } else {
            throw new Exception\RuntimeException(
                'Task does not specifiy an adapter and no default adapter was registered'
            );
        }

        $adapters = $this->getAdapters();

        if (!$adapters->hasAdapter($adapter)) {
            throw new Exception\RuntimeException(
                sprintf('No adapter registered for type "%s"', $adapter)
            );
        }

        return $adapters->getAdapter($adapter);
    }
}
