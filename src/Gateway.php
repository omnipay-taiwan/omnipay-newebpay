<?php

namespace Omnipay\NewebPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\NewebPay\Message\AuthorizeRequest;
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
}
