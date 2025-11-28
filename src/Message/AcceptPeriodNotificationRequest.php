<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Message\NotificationInterface;

class AcceptPeriodNotificationRequest extends CompletePurchaseRequest implements NotificationInterface
{
    public function sendData($data)
    {
        return $this->response = new AcceptPeriodNotificationResponse($this, $data);
    }

    public function getTransactionId()
    {
        return $this->getNotificationResponse()->getTransactionId();
    }

    public function getTransactionReference()
    {
        return $this->getNotificationResponse()->getTransactionReference();
    }

    public function getTransactionStatus()
    {
        return $this->getNotificationResponse()->getTransactionStatus();
    }

    public function getMessage()
    {
        return $this->getNotificationResponse()->getMessage();
    }

    /**
     * @return AcceptPeriodNotificationResponse
     */
    private function getNotificationResponse()
    {
        return ! $this->response ? $this->send() : $this->response;
    }
}
