<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Message\NotificationInterface;

class AcceptNotificationResponse extends CompletePurchaseResponse implements NotificationInterface
{
    public function getTransactionStatus()
    {
        return $this->isSuccessful() ? self::STATUS_COMPLETED : self::STATUS_FAILED;
    }

    // === 定期定額通知專用方法 ===

    /**
     * 商店訂單編號_期數.
     */
    public function getOrderNo()
    {
        return $this->data['OrderNo'] ?? null;
    }

    /**
     * 總期數.
     */
    public function getTotalTimes()
    {
        return isset($this->data['TotalTimes']) ? (int) $this->data['TotalTimes'] : null;
    }

    /**
     * 已授權次數.
     */
    public function getAlreadyTimes()
    {
        return isset($this->data['AlreadyTimes']) ? (int) $this->data['AlreadyTimes'] : null;
    }

    /**
     * 授權金額.
     */
    public function getAuthAmt()
    {
        return isset($this->data['AuthAmt']) ? (int) $this->data['AuthAmt'] : null;
    }

    /**
     * 授權日期.
     */
    public function getAuthDate()
    {
        return $this->data['AuthDate'] ?? null;
    }

    /**
     * 下次授權日期.
     */
    public function getNextAuthDate()
    {
        return $this->data['NextAuthDate'] ?? null;
    }
}
