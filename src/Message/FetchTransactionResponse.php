<?php

namespace Omnipay\NewebPay\Message;

class FetchTransactionResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return (int) $this->getCode() === 1;
    }

    public function isCancelled()
    {
        return (int) $this->getCode() === 6;
    }

    public function getCode()
    {
        return $this->data['TradeStatus'];
    }

    public function getMessage()
    {
        $lookup = [
            0 => '未付款',
            1 => '付款成功',
            2 => '付款失敗',
            3 => '取消付款',
            6 => '退款',
        ];

        return $lookup[$this->getCode()];
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
