<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Traits\HasDefaults;

class GetPaymentInfoRequest extends AbstractRequest
{
    use HasDefaults;

    /**
     * @throws InvalidResponseException
     */
    public function getData()
    {
        $data = $this->httpRequest->request->all();
        $tradeSha = $this->tradeSha($data['TradeInfo']);
        if (! hash_equals($tradeSha, $data['TradeSha'])) {
            throw new InvalidResponseException('Incorrect TradeSha');
        }

        return $this->decodeResponse($this->decrypt($data['TradeInfo']));
    }

    public function sendData($data)
    {
        return $this->response = new GetPaymentInfoResponse($this, $data);
    }
}
