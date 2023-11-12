<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Encryptor;
use Omnipay\NewebPay\Traits\HasDefaults;

class CompletePurchaseRequest extends AbstractRequest
{
    use HasDefaults;

    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    /**
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $this->decrypt($data));
    }

    /**
     * @throws InvalidResponseException
     */
    protected function decrypt($data)
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());
        $tradeSha = $encryptor->tradeSha($data['TradeInfo']);

        if (! hash_equals($tradeSha, $data['TradeSha'])) {
            throw new InvalidResponseException();
        }

        $data['Result'] = [];
        parse_str($encryptor->decrypt($data['TradeInfo']), $data['Result']);

        return $data;
    }
}
