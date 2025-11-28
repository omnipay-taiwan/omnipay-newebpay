<?php

namespace Omnipay\NewebPay\Message;

class AlterPeriodStatusResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getCode() === 'SUCCESS';
    }

    public function getCode()
    {
        return $this->data['Status'] ?? null;
    }

    public function getMessage()
    {
        return $this->data['Message'] ?? null;
    }

    public function getTransactionId()
    {
        return $this->data['MerOrderNo'] ?? null;
    }

    /**
     * 委託單號.
     */
    public function getPeriodNo()
    {
        return $this->data['PeriodNo'] ?? null;
    }

    /**
     * 委託狀態.
     * suspend=暫停
     * terminate=終止
     * restart=啟用
     */
    public function getAlterType()
    {
        return $this->data['AlterType'] ?? null;
    }
}
