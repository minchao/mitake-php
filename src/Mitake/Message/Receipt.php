<?php

namespace Mitake\Message;

/**
 * Class Receipt
 * @package Mitake\Message
 */
class Receipt
{
    /**
     * @var string
     */
    protected $msgid;

    /**
     * @var string
     */
    protected $dstaddr;

    /**
     * @var string
     */
    protected $dlvtime;

    /**
     * @var string
     */
    protected $donetime;

    /**
     * @var StatusCode
     */
    protected $statuscode;

    /**
     * @var string
     */
    protected $statusstr;

    /**
     * @var string
     */
    protected $statusFlag;

    /**
     * @return string
     */
    public function getMsgid()
    {
        return $this->msgid;
    }

    /**
     * @param string $msgid
     *
     * @return $this
     */
    public function setMsgid($msgid)
    {
        $this->msgid = $msgid;

        return $this;
    }

    /**
     * @return string
     */
    public function getDstaddr()
    {
        return $this->dstaddr;
    }

    /**
     * @param string $dstaddr
     *
     * @return $this
     */
    public function setDstaddr($dstaddr)
    {
        $this->dstaddr = $dstaddr;

        return $this;
    }

    /**
     * @return string
     */
    public function getDlvtime()
    {
        return $this->dlvtime;
    }

    /**
     * @param string $dlvtime
     *
     * @return $this
     */
    public function setDlvtime($dlvtime)
    {
        $this->dlvtime = $dlvtime;

        return $this;
    }

    /**
     * @return string
     */
    public function getDonetime()
    {
        return $this->donetime;
    }

    /**
     * @param string $donetime
     *
     * @return $this
     */
    public function setDonetime($donetime)
    {
        $this->donetime = $donetime;

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
     *
     * @return $this
     */
    public function setStatuscode($statuscode)
    {
        $this->statuscode = $statuscode;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusstr()
    {
        return $this->statusstr;
    }

    /**
     * @param string $statusstr
     *
     * @return $this
     */
    public function setStatusstr($statusstr)
    {
        $this->statusstr = $statusstr;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusFlag()
    {
        return $this->statusFlag;
    }

    /**
     * @param string $statusFlag
     *
     * @return $this
     */
    public function setStatusFlag($statusFlag)
    {
        $this->statusFlag = $statusFlag;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'msgid' => $this->msgid,
            'dstaddr' => $this->dstaddr,
            'dlvtime' => $this->dlvtime,
            'donetime' => $this->donetime,
            'statuscode' => (is_null($this->statuscode)) ? null : $this->statuscode->__toString(),
            'statusstr' => $this->statusstr,
            'StatusFlag' => $this->statusFlag,
        ];
    }
}
