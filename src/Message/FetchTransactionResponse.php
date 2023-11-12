<?php

namespace Omnipay\NewebPay\Message;

class FetchTransactionResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->getCode() === '00';
    }

    public function getCode()
    {
        return $this->data['Result']['RespondCode'];
    }

    public function getMessage()
    {
        return $this->data['Result']['RespondMsg'];
    }

    public function getTransactionId()
    {
        return $this->data['Result']['MerchantOrderNo'];
    }

    public function getTransactionReference()
    {
        return $this->data['Result']['TradeNo'];
    }
}
