<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Abstract Request
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $liveEndpoint = 'https://core.newebpay.com/';

    protected $testEndpoint = 'https://ccore.newebpay.com/';

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @param  ResponseInterface|string  $response
     * @return array
     */
    protected function decodeResponse($response)
    {
        $responseText = ! $response instanceof ResponseInterface
            ? $response
            : trim((string) $response->getBody());

        $data = json_decode($responseText, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $result = array_merge(['Status' => $data['Status'], 'Message' => $data['Message']], $data['Result']);
        } else {
            $result = [];
            parse_str($responseText, $result);
        }

        return $result;
    }
}
