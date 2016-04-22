<?php

namespace Detail\FileConversion\Client\Response;

use DateTime;

class Job extends BaseResponse
{
    /**
     * @var array
     */
    protected $actions;

    /**
     * @var array
     */
    protected $results;

    /**
     * @var array
     */
    protected $notifications;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getResult('id');
    }

    /**
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->getResult('source_url');
    }

    /**
     * @return array
     */
    public function getSourceMeta()
    {
        return $this->getResult('source_meta');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getResult('status');
    }

    /**
     * @return integer
     */
    public function getActionCount()
    {
        return (int) $this->getResult('action_count');
    }

    /**
     * @param boolean $asPlainResult
     * @return Action[]|array
     */
    public function getActions($asPlainResult = false)
    {
        return $this->getSubResults('actions', array($this, 'createAction'), $asPlainResult);
    }

    /**
     * @return integer
     */
    public function getNotificationCount()
    {
        return (int) $this->getResult('notification_count');
    }

    /**
     * @param boolean $asPlainResult
     * @return Notification[]|array
     */
    public function getNotifications($asPlainResult = false)
    {
        return $this->getSubResults('notifications', array($this, 'createNotification'), $asPlainResult, false);
    }

    /**
     * @return integer
     */
    public function getResultCount()
    {
        return (int) $this->getResult('result_count');
    }

    /**
     * @param boolean $asPlainResult
     * @return Result[]|array
     */
    public function getResults($asPlainResult = false)
    {
        return $this->getSubResults('results', array($this, 'createResult'), $asPlainResult, false);
    }

    /**
     * @return DateTime
     */
    public function getSubmittedOn()
    {
        return $this->getDateResult('submitted_on');
    }

    /**
     * @return DateTime
     */
    public function getProcessingStartedOn()
    {
        return $this->getDateResult('processing_started_on', false);
    }

    /**
     * @return DateTime
     */
    public function getProcessingCompletedOn()
    {
        return $this->getDateResult('processing_completed_on', false);
    }

    /**
     * @return DateTime
     */
    public function getCompletedOn()
    {
        return $this->getDateResult('completed_on', false);
    }

    /**
     * @param array $data
     * @return Action
     */
    protected function createAction(array $data)
    {
        return Action::fromResult($data);
    }

    /**
     * @param array $data
     * @return Notification
     */
    protected function createNotification(array $data)
    {
        return Notification::fromResult($data);
    }

    /**
     * @param array $data
     * @return Result
     */
    protected function createResult(array $data)
    {
        return Result::fromResult($data);
    }
}
