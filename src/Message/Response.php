<?php

namespace Mitake\Message;

/**
 * Class Response
 * @package Mitake\Message
 */
class Response
{
    /**
     * @var Result[] Results
     */
    protected $results = [];

    /**
     * @var integer
     */
    protected $accountPoint;

    /**
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Result[] $results
     * @return $this
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * @param Result $result
     * @return $this
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;

        return $this;
    }

    /**
     * @return int
     */
    public function getAccountPoint()
    {
        return $this->accountPoint;
    }

    /**
     * @param int $accountPoint
     * @return $this
     */
    public function setAccountPoint($accountPoint)
    {
        $this->accountPoint = $accountPoint;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'results' => array_map(function (Result $result) {
                return $result->toArray();
            }, $this->results),
            'accountPoint' => $this->accountPoint,
        ];
    }
}
