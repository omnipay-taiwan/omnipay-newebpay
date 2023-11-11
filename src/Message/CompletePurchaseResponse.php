<?php

namespace Omnipay\NewebPay\Message;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getCode() === '00';
    }

    public function getMessage()
    {
        return $this->data['Result']['Message'];
    }

    public function getCode()
    {
        return $this->data['Result']['RespondCode'];
    }

    public function getTransactionReference()
    {
        return $this->data['Result']['TradeNo'];
    }

    public function getTransactionId()
    {
        return $this->data['Result']['MerchantOrderNo'];
    }
}
