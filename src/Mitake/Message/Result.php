<?php

namespace Mitake\Message;

/**
 * Class Result
 * @package Mitake\Message
 */
class Result
{
    /**
     * @var string
     */
    protected $msgid;

    /**
     * @var StatusCode
     */
    protected $statuscode;

    /**
     * @return string
     */
    public function getMsgid()
    {
        return $this->msgid;
    }

    /**
     * @param string $msgid
     * @return $this
     */
    public function setMsgid($msgid)
    {
        $this->msgid = $msgid;

        return $this;
    }

    /**
     * @return StatusCode
     */
    public function getStatuscode()
    {
        return $this->statuscode;
    }

    /**
     * @param StatusCode $statuscode
     * @return $this
     */
    public function setStatuscode(StatusCode $statuscode)
    {
        $this->statuscode = $statuscode;

        return $this;
    }
}
