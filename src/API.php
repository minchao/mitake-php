<?php

namespace Mitake;

use Mitake\Message;
use Mitake\Exception\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class API
 * @package Mitake
 */
class API
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * API constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send multiple SMS
     *
     * @param Message\Message[] $messages
     * @return Message\Response
     * @throws Exception\BadResponseException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendBatch(array $messages)
    {
        $body = '';
        /** @var Message\Message $message */
        foreach ($messages as $i => $message) {
            if (!$message instanceof Message\Message) {
                throw new InvalidArgumentException();
            }

            $body .= "[{$i}]\n";
            $body .= $message->toINI();
        }
        $body = trim($body);

        $request = $this->client->newRequest(
            'POST',
            $this->client->buildUriWithQuery('/SmSendPost.asp', ['encoding' => 'UTF8']),
            'text/plain',
            $body
        );

        $response = $this->client->sendRequest($request);

        return $this->parseMessageResponse($response);
    }

    /**
     * Send an SMS
     *
     * @param Message\Message $message
     * @return Message\Response
     * @throws Exception\BadResponseException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(Message\Message $message)
    {
        $request = $this->client->newRequest(
            'POST',
            $this->client->buildUriWithQuery('/api/mtk/SmSend', ['CharsetURL' => 'UTF-8'] + $message->toArray()),
            'application/x-www-form-urlencoded'
        );

        $response = $this->client->sendRequest($request);

        return $this->parseMessageResponse($response);
    }

    /**
     * @param Message\LongMessage[] $messages
     * @return Message\Response
     * @throws Exception\BadResponseException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendLongMessageBatch(array $messages)
    {
        $body = '';
        foreach ($messages as $message) {
            if (!$message instanceof Message\LongMessage) {
                throw new InvalidArgumentException();
            }

            $body .= $message->toINI();
        }
        $body = trim($body);

        $request = $this->client->newRequest(
            'POST',
            $this->client->buildUriWithQuery(
                $this->client->getLongMessageBaseURL() . '/SpLmPost',
                ['Encoding_PostIn' => 'UTF8']
            ),
            'text/plain',
            $body
        );

        $response = $this->client->sendRequest($request);

        return $this->parseMessageResponse($response);
    }

    /**
     * @param Message\LongMessage $message
     * @return Message\Response
     * @throws Exception\BadResponseException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendLongMessage(Message\LongMessage $message)
    {
        return $this->sendLongMessageBatch([$message]);
    }

    /**
     * Retrieve your account balance
     *
     * @return integer
     * @throws Exception\BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function queryAccountPoint()
    {
        $request = $this->client->newRequest(
            'GET',
            $this->client->buildUriWithQuery('/SmQueryGet.asp')
        );

        $response = $this->client->sendRequest($request);
        $contents = $response->getBody()->getContents();
        $data = explode("=", $contents);

        return $data[1];
    }

    /**
     * Fetch the status of specific messages
     *
     * @param string[] $ids
     * @return Message\StatusResponse
     * @throws Exception\BadResponseException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function queryMessageStatus(array $ids)
    {
        $request = $this->client->newRequest(
            'GET',
            $this->client->buildUriWithQuery('/SmQueryGet.asp', ['msgid' => implode(',', $ids)])
        );

        $response = $this->client->sendRequest($request);

        return $this->parseMessageStatusResponse($response);
    }

    /**
     * @param string[] $ids
     * @return Message\StatusResponse
     * @throws Exception\BadResponseException
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelMessageStatus(array $ids)
    {
        $request = $this->client->newRequest(
            'GET',
            $this->client->buildUriWithQuery('/SmCancel.asp', ['msgid' => implode(",", $ids)])
        );

        $response = $this->client->sendRequest($request);

        return $this->parseCancelMessageStatusResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    protected function getBodyContents(ResponseInterface $response)
    {
        $contents = $response->getBody()->getContents();
        return mb_convert_encoding($contents, 'UTF-8', 'BIG-5');
    }

    /**
     * @param ResponseInterface $response
     * @return Message\Response
     * @throws InvalidArgumentException
     */
    protected function parseMessageResponse(ResponseInterface $response)
    {
        $contents = $this->getBodyContents($response);
        $iniArray = parse_ini_string($contents, true);

        $resp = new Message\Response();

        foreach ($iniArray as $msg) {
            $result = new Message\Result();
            $result->setMsgid($msg['msgid'])
                ->setStatuscode(new Message\StatusCode($msg['statuscode']));

            $resp->addResult($result);
            if (isset($msg['AccountPoint'])) {
                $resp->setAccountPoint($msg['AccountPoint']);
            }
        }

        return $resp;
    }

    /**
     * @param ResponseInterface $response
     * @return Message\StatusResponse
     * @throws InvalidArgumentException
     */
    protected function parseMessageStatusResponse(ResponseInterface $response)
    {
        $resp = new Message\StatusResponse();

        $lines = explode("\n", trim($this->getBodyContents($response)));
        foreach ($lines as $line) {
            $line = trim($line);
            list($msgID, $statusCode, $statusTime) = explode("\t", $line);

            $status = new Message\Status();
            $status->setMsgid($msgID)
                ->setStatuscode(new Message\StatusCode($statusCode))
                ->setStatustime($statusTime);

            $resp->addStatus($status);
        }

        return $resp;
    }

    /**
     * @param ResponseInterface $response
     * @return Message\StatusResponse
     * @throws InvalidArgumentException
     */
    protected function parseCancelMessageStatusResponse(ResponseInterface $response)
    {
        $resp = new Message\StatusResponse();

        $lines = explode("\n", trim($this->getBodyContents($response)));
        foreach ($lines as $line) {
            $line = trim($line);
            list($msgID, $statusCode) = explode('=', $line);

            $status = new Message\Status();
            $status->setMsgid($msgID)
                ->setStatuscode(new Message\StatusCode($statusCode));

            $resp->addStatus($status);
        }

        return $resp;
    }
}
