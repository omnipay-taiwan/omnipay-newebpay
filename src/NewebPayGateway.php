<?php

namespace Omnipay\NewebPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\NewebPay\Message\AuthorizeRequest;

/**
 * NewebPay Gateway
 */
class NewebPayGateway extends AbstractGateway
{
    public function getName()
    {
        return 'NewebPay';
    }

    public function getDefaultParameters()
    {
        return [
            'key' => '',
            'testMode' => false,
        ];
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }

    /**
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }
}
