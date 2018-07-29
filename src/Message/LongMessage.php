<?php

namespace Mitake\Message;

class LongMessage extends Message
{
    /**
     * @var string Message ID
     */
    protected $id;

    /**
     * @var string Receiver name
     */
    protected $destname;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestname()
    {
        return $this->destname;
    }

    /**
     * @param string $destname
     * @return $this
     */
    public function setDestname($destname)
    {
        $this->destname = $destname;

        return $this;
    }

    /**
     * @return string
     */
    public function toINI()
    {
        $smBody = str_replace("\n", chr(6), $this->smbody);

        return implode('$$', [
                (string)$this->id,
                (string)$this->dstaddr,
                (string)$this->dlvtime,
                (string)$this->vldtime,
                (string)$this->destname,
                (string)$this->response,
                (string)$smBody,
            ]) . "\n";
    }
}
