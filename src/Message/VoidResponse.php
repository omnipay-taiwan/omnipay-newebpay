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
        return array_key_exists('Result', $this->data)
            ? $this->data['Result']['TradeNo']
            : $this->data['TradeNo'];
    }

    public function getTransactionId()
    {
        return array_key_exists('Result', $this->data)
            ? $this->data['Result']['MerchantOrderNo']
            : $this->data['MerchantOrderNo'];
    }
}
