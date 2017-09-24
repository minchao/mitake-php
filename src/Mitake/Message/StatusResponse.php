<?php

namespace Mitake\Message;

/**
 * Class StatusResponse
 * @package Mitake\Message
 */
class StatusResponse
{
    /**
     * @var array Status
     */
    protected $statuses = [];

    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @param array $statuses
     * @return $this
     */
    public function setStatuses($statuses)
    {
        $this->statuses = $statuses;

        return $this;
    }

    /**
     * @param Status $status
     * @return $this
     */
    public function addStatus(Status $status)
    {
        $this->statuses[] = $status;

        return $this;
    }
}
