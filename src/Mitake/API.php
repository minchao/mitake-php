<?php

namespace Mitake;

use Mitake\Exception\InvalidArgumentException;
use Mitake\Message;
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
     * @param array $messages
     * @return Message\Response
     * @throws InvalidArgumentException
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
            '/SmSendPost.asp' . $this->client->buildQuery(['encoding' => 'UTF8']),
            'text/plain',
            $body
        );

        $response = $this->client->send($request);

        return $this->parseMessageResponse($response);
    }

    /**
     * Send an SMS
     *
     * @param Message\Message $message
     * @return Message\Response
     */
    public function send(Message\Message $message)
    {
        return $this->sendBatch([$message]);
    }

    /**
     * Retrieve your account balance
     *
     * @return integer
     */
    public function queryAccountPoint()
    {
        $request = $this->client->newRequest(
            'GET',
            '/SmQueryGet.asp' . $this->client->buildQuery()
        );

        $response = $this->client->send($request);
        $contents = $response->getBody()->getContents();
        $data = explode("=", $contents);

        return $data[1];
    }

    /**
     * Fetch the status of specific messages
     *
     * @param array $ids
     * @return Message\StatusResponse
     */
    public function queryMessageStatus(array $ids)
    {
        $request = $this->client->newRequest(
            'GET',
            'SmQueryGet.asp' . $this->client->buildQuery(['msgid' => implode(",", $ids)])
        );

        $response = $this->client->send($request);

        return $this->parseMessageStatusResponse($response);
    }

    /**
     * @param array $ids
     * @return Message\StatusResponse
     */
    public function cancelMessageStatus(array $ids)
    {
        $request = $this->client->newRequest(
            'GET',
            'SmCancel.asp' . $this->client->buildQuery(['msgid' => implode(",", $ids)])
        );

        $response = $this->client->send($request);

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
     */
    protected function parseMessageResponse(ResponseInterface $response)
    {
        $contents = $this->getBodyContents($response);
        $iniArray = parse_ini_string($contents, true);

        $resp = new Message\Response();

        foreach ($iniArray as $key => $msg) {
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
     */
    protected function parseMessageStatusResponse(ResponseInterface $response)
    {
        $resp = new Message\StatusResponse();

        $lines = explode("\n", $this->getBodyContents($response));
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
     */
    protected function parseCancelMessageStatusResponse(ResponseInterface $response)
    {
        $resp = new Message\StatusResponse();

        $lines = explode("\n", $this->getBodyContents($response));
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
