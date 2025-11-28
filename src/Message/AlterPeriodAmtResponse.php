<?php

namespace Omnipay\NewebPay\Message;

class AlterPeriodAmtResponse extends AbstractResponse
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
     * 委託金額.
     */
    public function getAlterAmt()
    {
        return isset($this->data['AlterAmt']) ? (int) $this->data['AlterAmt'] : null;
    }

    /**
     * 週期類別.
     */
    public function getPeriodType()
    {
        return $this->data['PeriodType'] ?? null;
    }

    /**
     * 交易週期授權時間.
     */
    public function getPeriodPoint()
    {
        return $this->data['PeriodPoint'] ?? null;
    }

    /**
     * 下一期授權金額.
     */
    public function getNewNextAmt()
    {
        return isset($this->data['NewNextAmt']) ? (int) $this->data['NewNextAmt'] : null;
    }

    /**
     * 下一期授權時間.
     */
    public function getNewNextTime()
    {
        return $this->data['NewNextTime'] ?? null;
    }

    /**
     * 授權期數.
     */
    public function getPeriodTimes()
    {
        return isset($this->data['PeriodTimes']) ? (int) $this->data['PeriodTimes'] : null;
    }

    /**
     * 信用卡到期日.
     */
    public function getExtday()
    {
        return $this->data['ExtDay'] ?? null;
    }

    /**
     * 每期授權結果通知網址.
     */
    public function getNotifyURL()
    {
        return $this->data['NotifyURL'] ?? null;
    }
}
