<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Encryptor;
use Omnipay\NewebPay\Traits\HasDefaults;

class CompletePurchaseRequest extends AbstractRequest
{
    use HasDefaults;

    public function getData(): array
    {
        return $this->httpRequest->request->all();
    }

    /**
     * @throws InvalidResponseException
     */
    public function sendData($data): CompletePurchaseResponse
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());

        if (! $encryptor->check($data['TradeInfo'], $data['TradeSha'])) {
            throw new InvalidResponseException();
        }

        $data['Result'] = [];
        parse_str($encryptor->decrypt($data['TradeInfo']), $data['Result']);

        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
