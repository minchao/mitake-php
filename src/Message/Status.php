<?php

namespace Mitake\Message;

/**
 * Class Status
 * @package Mitake\Message
 */
class Status extends Result
{
    /**
     * @var string
     */
    protected $statustime;

    /**
     * @return string
     */
    public function getStatustime()
    {
        return $this->statustime;
    }

    /**
     * @param string $statustime
     * @return $this
     */
    public function setStatustime($statustime)
    {
        $this->statustime = $statustime;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();
        $arr['statustime'] = $this->getStatustime();

        return $arr;
    }
}
