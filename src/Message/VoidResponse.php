<?php

namespace Omnipay\NewebPay\Message;

class VoidResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return $this->getCode() === 'SUCCESS';
    }

    public function getCode()
    {
        return $this->data['Status'];
    }

    public function getMessage()
    {
        return $this->data['Message'];
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
