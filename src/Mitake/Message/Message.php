<?php

namespace Mitake\Message;

/**
 * Class Message
 * @package Mitake
 */
class Message
{
    /**
     * @var string Destination phone number
     */
    protected $dstaddr;

    /**
     * @var string The text of the message you want to send
     */
    protected $smbody;

    /**
     * @var string Optional, Delivery time
     */
    protected $dlvtime;

    /**
     * @var string Optional
     */
    protected $vldtime;

    /**
     * @var string Optional, Callback URL to receive the delivery receipt of the message
     */
    protected $response;

    /**
     * @return string
     */
    public function getDstaddr()
    {
        return $this->dstaddr;
    }

    /**
     * @param string $dstaddr
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
    public function getSmbody()
    {
        return $this->smbody;
    }

    /**
     * @param string $smbody
     * @return $this
     */
    public function setSmbody($smbody)
    {
        $this->smbody = $smbody;

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
    public function getVldtime()
    {
        return $this->vldtime;
    }

    /**
     * @param string $vldtime
     * @return $this
     */
    public function setVldtime($vldtime)
    {
        $this->vldtime = $vldtime;

        return $this;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * toINI returns the INI format string from the message fields
     *
     * @return string
     */
    public function toINI()
    {
        $ini = '';
        $ini .= "dstaddr={$this->dstaddr}\n";
        $ini .= "smbody={$this->smbody}\n";
        if (!empty($this->dlvtime)) {
            $ini .= "dlvtime={$this->dlvtime}\n";
        }
        if (!empty($this->vldtime)) {
            $ini .= "vldtime={$this->vldtime}\n";
        }
        if (!empty($this->response)) {
            $ini .= "response={$this->response}\n";
        }

        return $ini;
    }
}
