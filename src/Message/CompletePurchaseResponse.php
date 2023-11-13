<?php

namespace Omnipay\NewebPay\Message;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getCode() === 'SUCCESS';
    }

    public function getMessage()
    {
        return $this->data['Message'];
    }

    public function getCode()
    {
        return $this->data['Status'];
    }

    public function getTransactionReference()
    {
        return $this->data['TradeNo'];
    }

    public function getTransactionId()
    {
        return $this->data['MerchantOrderNo'];
    }
}
