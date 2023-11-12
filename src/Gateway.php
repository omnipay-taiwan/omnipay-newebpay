<?php

namespace Omnipay\NewebPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\NewebPay\Message\AcceptNotificationRequest;
use Omnipay\NewebPay\Message\AuthorizeRequest;
use Omnipay\NewebPay\Message\CompletePurchaseRequest;
use Omnipay\NewebPay\Message\FetchTransactionRequest;
use Omnipay\NewebPay\Message\PurchaseRequest;
use Omnipay\NewebPay\Message\VoidRequest;
use Omnipay\NewebPay\Traits\HasDefaults;

/**
 * NewebPay Gateway
 */
class Gateway extends AbstractGateway
{
    use HasDefaults;

    public function getName()
    {
        return 'NewebPay';
    }

    public function getDefaultParameters(): array
    {
        return [
            'HashKey' => '',
            'HashIV' => '',
            'MerchantID' => '',
            'testMode' => false,
        ];
    }

    /**
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }

    public function purchase(array $options = []): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options = []): RequestInterface
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    public function acceptNotification(array $options = []): NotificationInterface
    {
        return $this->createRequest(AcceptNotificationRequest::class, $options);
    }

    public function fetchTransaction(array $options = []): RequestInterface
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    public function void(array $options = []): RequestInterface
    {
        return $this->createRequest(VoidRequest::class, $options);
    }
}
