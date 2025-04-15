<?php

namespace Omnipay\NewebPay\Message;

class GetPaymentInfoResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return false;
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
