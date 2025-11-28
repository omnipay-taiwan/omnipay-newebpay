<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Message\NotificationInterface;

class AcceptNotificationRequest extends CompletePurchaseRequest implements NotificationInterface
{
    public function sendData($data)
    {
        return $this->response = new AcceptNotificationResponse($this, $data);
    }

    /**
     * 判斷是否為定期定額通知.
     *
     * @return bool
     */
    protected function isPeriod(): bool
    {
        return $this->httpRequest->request->has('Period');
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
     * @return AcceptNotificationResponse
     */
    private function getNotificationResponse()
    {
        return ! $this->response ? $this->send() : $this->response;
    }
}
