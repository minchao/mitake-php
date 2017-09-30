<?php

require __DIR__ . '/../vendor/autoload.php';

use function GuzzleHttp\Psr7\parse_query;
use Mitake\Message\Receipt;
use Mitake\Message\StatusCode;
use Slim\Http\Request;
use Slim\Http\Response;

$app = new Slim\App();

$app->get('/callback', function (Request $request, Response $response, $args) {
    $params = parse_query($request->getUri()->getQuery());
    if (!isset($params['msgid'])) {
        return $response->withStatus(400)
            ->withJson([
                'error' => 'invalid request',
                'uri' => $request->getUri()->__toString(),
            ]);
    }

    $receipt = new Receipt();
    $receipt->setMsgid($params['msgid']);
    if (isset($params['dstaddr'])) {
        $receipt->setDstaddr($params['dstaddr']);
    }
    if (isset($params['dlvtime'])) {
        $receipt->setDlvtime($params['dlvtime']);
    }
    if (isset($params['donetime'])) {
        $receipt->setDonetime($params['donetime']);
    }
    if (isset($params['statuscode'])) {
        $receipt->setStatuscode(new StatusCode($params['statuscode']));
    }
    if (isset($params['statusstr'])) {
        $receipt->setStatusstr($params['statusstr']);
    }
    if (isset($params['StatusFlag'])) {
        $receipt->setStatusFlag($params['StatusFlag']);
    }

    return $response->withJson($receipt->toArray());
});

$app->run();
