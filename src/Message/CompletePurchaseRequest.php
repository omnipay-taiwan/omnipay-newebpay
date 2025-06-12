<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasDefaults;

class CompletePurchaseRequest extends AbstractRequest
{
    use HasDefaults;

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $tradeInfo = $this->httpRequest->request->get('TradeInfo');
        if (! hash_equals($this->tradeSha($tradeInfo), $this->httpRequest->get('TradeSha', ''))) {
            throw new InvalidRequestException('Incorrect TradeSha');
        }

        return $this->decodeResponse($this->decrypt($tradeInfo));
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
