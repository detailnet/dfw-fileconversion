<?php

namespace Detail\FileConversion\Processing;

use DateInterval;
use DateTime;

class TaskProcessor implements
    TaskProcessorInterface,
    PausableTaskProcessorInterface
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
     * @var DateInterval|false
     */
    protected $pauseOnIncident = false;

    /**
     * @var DateTime|boolean
     */
    protected $pausedUntil = false;

    /**
     * @param AdapterManagerInterface $adapters
     * @param string $defaultAdapter
     * @param string|DateInterval $pauseOnIncident
     */
    public function __construct(
        AdapterManagerInterface $adapters,
        $defaultAdapter = null,
        $pauseOnIncident = null
    ) {
        $this->setAdapters($adapters);

        if ($defaultAdapter !== null) {
            $this->setDefaultAdapter($defaultAdapter);
        }

        if ($pauseOnIncident !== null) {
            $this->setPauseOnIncident($pauseOnIncident);
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
     * @return DateInterval
     */
    public function getPauseOnIncident()
    {
        return $this->pauseOnIncident;
    }

    /**
     * @param string|DateInterval $pauseOnIncident
     */
    public function setPauseOnIncident($pauseOnIncident)
    {
        if (is_string($pauseOnIncident)) {
            $pauseOnIncident = DateInterval::createFromDateString($pauseOnIncident);
        } elseif (!$pauseOnIncident instanceof DateInterval && $pauseOnIncident !== false) {
            throw new Exception\InvalidArgumentException(
                '$pauseOnIncident must be false, a valid date string or DateInterval object'
            );
        }

        $this->pauseOnIncident = $pauseOnIncident;
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
        $adapter = $this->getAdapter($task);
        $support = $adapter->supportsTask($task);

        if (!$support->isSupported()) {
            $message = sprintf(
                'Adapter %s does not support given task (process identifier: %s)',
                get_class($adapter),
                $task->getProcessId()
            );

            $reason = $support->getMessage();

            if ($reason) {
                $message .=  ': ' . $reason;
            }

            throw new Exception\ProcessingFailedException($message);
        }

        $processId = $this->callAdapter($adapter, __FUNCTION__, array($task));

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
        $result = $this->callAdapter($adapter, __FUNCTION__, array($task));

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
        $result = $this->callAdapter($adapter, __FUNCTION__, array($task, $data), false);

        return $result;
    }

    /**
     * @param DateTime $until
     * @return DateTime
     */
    public function pauseProcessing(DateTime $until = null)
    {
        if ($until === null) {
            $pauseInterval = $this->getPauseOnIncident();

            if ($pauseInterval instanceof DateInterval) {
                $until = new DateTime();
                $until->add($pauseInterval);
            } else {
                $until = true;
            }
        }

        $this->pausedUntil = $until;

        return $until;
    }

    /**
     * @return void
     */
    public function resumeProcessing()
    {
        $this->pausedUntil = false;
    }

    /**
     * @return DateTime|boolean
     */
    public function getPausedUntil()
    {
        // Resume when paused until set date/time.
        if ($this->pausedUntil instanceof DateTime
            && $this->pausedUntil < new DateTime()
        ) {
            $this->resumeProcessing();
        }

        return $this->pausedUntil;
    }

    /**
     * @return boolean
     */
    public function isPaused()
    {
        return $this->getPausedUntil() !== false;
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

    /**
     * @param Adapter\AdapterInterface $adapter
     * @param string $method
     * @param array $arguments
     * @param boolean $considerPaused
     * @return mixed
     */
    protected function callAdapter(
        Adapter\AdapterInterface $adapter,
        $method,
        array $arguments = array(),
        $considerPaused = true
    ) {
        if ($considerPaused !== false && $this->isPaused()) {
            $pausedUntil = $this->getPausedUntil();
            $pausedMessage = 'Processing is paused';

            if ($pausedUntil instanceof DateTime) {
                $pausedMessage .= sprintf(' until %s', $pausedUntil->format('Y-m-d H:i:s'));
            }

            throw new Exception\ProcessingPausedException($pausedMessage);
        }

        $call = array($adapter, $method);

        if (!is_callable($call)) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Adapter method %s::%s() cannot be called',
                    get_class($adapter),
                    $method
                )
            );
        }

        /** @todo Or use wrapping adapter? (IncidentHandlingAdapter) */

        try {
            $result = call_user_func_array($call, $arguments);
        } catch (Exception\ProcessingUnavailableException $e) {
//            if ($this->getIncidentRepository() !== null) {
//                $this->getIncidentRepository()->add(
//                    $this->getIncidentRepository()->create()
//                );
//            }

            if ($this->getPauseOnIncident() !== false) {
                $this->pauseProcessing();

                throw new Exception\ProcessingPausedException(
                    sprintf(
                        'Paused processing due to an incident during %s: %s',
                        $method,
                        $e->getMessage()
                    ),
                    0,
                    $e
                );
            } else {
                throw $e;
            }
        }

        return $result;
    }
}
