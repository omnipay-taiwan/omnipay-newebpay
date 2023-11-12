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
        return $this->data['Result']['Message'];
    }

    public function getCode()
    {
        return $this->data['Status'];
    }

    public function getTransactionReference()
    {
        return array_key_exists('Result', $this->data['Result'])
            ? $this->data['Result']['Result']['TradeNo']
            : $this->data['Result']['TradeNo'];
    }

    public function getTransactionId()
    {
        return array_key_exists('Result', $this->data['Result'])
            ? $this->data['Result']['Result']['MerchantOrderNo']
            : $this->data['Result']['MerchantOrderNo'];
    }
}
