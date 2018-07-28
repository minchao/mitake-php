<?php

namespace Mitake\Message;

/**
 * Class StatusResponse
 * @package Mitake\Message
 */
class StatusResponse
{
    /**
     * @var Status[] Status
     */
    protected $statuses = [];

    /**
     * @return Status[]
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @param Status[] $statuses
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

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'statuses' => array_map(function (Status $status) {
                return $status->toArray();
            }, $this->getStatuses()),
        ];
    }
}
