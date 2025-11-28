<?php

namespace Omnipay\NewebPay\Message;

class CompletePeriodResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getCode() === 'SUCCESS';
    }

    public function getMessage()
    {
        return $this->data['Message'] ?? null;
    }

    public function getCode()
    {
        return $this->data['Status'] ?? null;
    }

    public function getTransactionReference()
    {
        return $this->data['TradeNo'] ?? null;
    }

    public function getTransactionId()
    {
        return $this->data['MerchantOrderNo'] ?? null;
    }

    /**
     * 委託單號.
     */
    public function getPeriodNo()
    {
        return $this->data['PeriodNo'] ?? null;
    }

    /**
     * 週期類別.
     */
    public function getPeriodType()
    {
        return $this->data['PeriodType'] ?? null;
    }

    /**
     * 每期金額.
     */
    public function getPeriodAmt()
    {
        return isset($this->data['PeriodAmt']) ? (int) $this->data['PeriodAmt'] : null;
    }

    /**
     * 授權次數.
     */
    public function getAuthTimes()
    {
        return $this->data['AuthTimes'] ?? null;
    }

    /**
     * 授權排程日期.
     */
    public function getDateArray()
    {
        return $this->data['DateArray'] ?? null;
    }

    /**
     * 授權時間.
     */
    public function getAuthTime()
    {
        return $this->data['AuthTime'] ?? null;
    }

    /**
     * 授權碼.
     */
    public function getAuthCode()
    {
        return $this->data['AuthCode'] ?? null;
    }

    /**
     * 銀行回應碼.
     */
    public function getRespondCode()
    {
        return $this->data['RespondCode'] ?? null;
    }

    /**
     * 卡號前六後四碼.
     */
    public function getCardNo()
    {
        return $this->data['CardNo'] ?? null;
    }

    /**
     * 款項保管銀行.
     */
    public function getEscrowBank()
    {
        return $this->data['EscrowBank'] ?? null;
    }

    /**
     * 收單機構.
     */
    public function getAuthBank()
    {
        return $this->data['AuthBank'] ?? null;
    }

    /**
     * 交易類別.
     */
    public function getPaymentMethod()
    {
        return $this->data['PaymentMethod'] ?? null;
    }
}
